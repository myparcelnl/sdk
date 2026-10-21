<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Ecommerce;

use gapple\StructuredFields\Bytes;
use gapple\StructuredFields\Dictionary;
use gapple\StructuredFields\InnerList;
use gapple\StructuredFields\Item;
use gapple\StructuredFields\Parser;
use MyParcelNL\Sdk\Crypto\HttpMessageSignature;
use MyParcelNL\Sdk\Exception\UnauthorizedCommandException;
use GuzzleHttp\ClientInterface;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Services\Connect\JwksProvider;
use MyParcelNL\Sdk\Services\Connect\JwksStorageInterface;
use RuntimeException;

/**
 * Proves that a request to your commandEndpoint comes from MyParcel and is meant for this shop.
 *
 * Call verify() before anything else reads the request, and reply by the exception it throws:
 *
 *     try {
 *         $verifier = new CommandRequestVerifier($connect, $jwksStorage);
 *         $verifier->verify($_SERVER['REQUEST_METHOD'], getallheaders(), $rawBody);
 *         $command = (new CommandReader())->read($rawBody);
 *     } catch (CommandResponseInterface $exception) {
 *         http_response_code($exception->getStatusCode()); // 401 for a failed check, 503 when the
 *         exit;                                            // keys could not be fetched
 *     }
 *
 * The signature is RFC 9421 with ecdsa-p256-sha256 over the method, your stored commandEndpoint, the
 * Content-Type and the RFC 9530 Content-Digest of the raw body. The key comes from MyParcel's JWKS,
 * through JwksProvider. The checks run in the order MyParcel prescribes: the cheap ones first, the
 * key fetch and the signature last.
 *
 * Never log the Signature header, and do not keep it after this returns. The exception messages
 * name the failed check for your own log: never put one in the response.
 *
 * This proves who sent the request, not that it is new: MyParcel resends a command after a
 * timeout, and a captured request stays valid for about a minute, longer when the clocks differ.
 * Drop duplicates yourself, by storing (connectionId, commandId) before you act on a command.
 */
final class CommandRequestVerifier
{
    /**
     * The one label MyParcel uses in Signature-Input and Signature.
     */
    private const LABEL = 'myparcel';

    private const ALGORITHM = 'ecdsa-p256-sha256';

    /**
     * The covered components, in this order and no others.
     */
    private const COMPONENTS = ['@method', '@target-uri', 'content-type', 'content-digest'];

    /**
     * How far created may be from our clock, and how long a signature may live, in seconds.
     */
    private const MAX_AGE = 60;

    private const DIGEST_ALGORITHM = 'sha-256';

    /**
     * @var \MyParcelNL\Sdk\Services\Connect\ConnectService
     */
    private $connect;

    /**
     * @var \MyParcelNL\Sdk\Services\Connect\JwksProvider
     */
    private $keys;

    /**
     * @param ConnectService       $connect     Holds the stored connection id and command endpoint,
     *                                          and the config that names the host the keys come from.
     * @param JwksStorageInterface $jwksStorage Your own implementation. See {@see JwksStorageInterface}.
     * @param ClientInterface|null $http        The HTTP client the key fetch goes out on. Pass one to
     *                                          set a proxy or a CA bundle.
     */
    public function __construct(ConnectService $connect, JwksStorageInterface $jwksStorage, ?ClientInterface $http = null)
    {
        $this->connect = $connect;
        $this->keys    = new JwksProvider($connect->getConfig(), $jwksStorage, $http);
    }

    /**
     * Check a request to the commandEndpoint. Returns when every check passed.
     *
     * @param  string                         $method  The request method, POST.
     * @param  array<string, string|string[]> $headers The request headers, by name in any casing.
     *                                                 A header that arrived more than once is a
     *                                                 list, as PSR-7 getHeaders() gives it.
     * @param  string                         $rawBody The body bytes exactly as received, before
     *                                                 any JSON parsing.
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException     A check failed. Reply 401.
     * @throws \MyParcelNL\Sdk\Exception\CommandCheckUnavailableException The keys could not be
     *                                                                     fetched. Reply 503.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException                 The stored state cannot be
     *                                                                     read.
     */
    public function verify(string $method, array $headers, string $rawBody): void
    {
        $targetUri = $this->connect->getCommandEndpoint();

        if (null === $targetUri) {
            throw new UnauthorizedCommandException('This shop has no command endpoint');
        }

        $headers = self::normalizeHeaders($headers);

        // Both headers are RFC 8941 dictionaries (gapple/structured-fields): members by key, where a
        // member is an Item (one value) or an InnerList (a parenthesised list), each with Parameters.
        $signatureInput = self::oneMember(self::dictionary($headers, 'signature-input'), self::LABEL);
        $signature      = self::oneMember(self::dictionary($headers, 'signature'), self::LABEL);

        if (!$signatureInput instanceof InnerList) {
            throw new UnauthorizedCommandException('Signature-Input is not an inner list');
        }

        self::assertComponents($signatureInput);

        $params = $signatureInput->getParameters();

        if (self::ALGORITHM !== $params->alg) {
            throw new UnauthorizedCommandException('The signature alg is not ' . self::ALGORITHM);
        }

        if (!is_int($params->created) || !is_int($params->expires) || !is_string($params->keyid)) {
            throw new UnauthorizedCommandException('The signature parameters have the wrong types');
        }

        self::assertTime($params->created, $params->expires, time());

        self::assertDigest(self::dictionary($headers, 'content-digest'), $rawBody);

        // Read before the key lookup, so a request without it costs no fetch.
        $contentType = self::header($headers, 'content-type');

        $key = $this->keys->findKey($params->keyid);

        if (null === $key) {
            throw new UnauthorizedCommandException('The signature keyid is not a MyParcel key');
        }

        $base = HttpMessageSignature::signatureBase($signatureInput, [
            '@method'        => $method,
            '@target-uri'    => $targetUri,
            'content-type'   => $contentType,
            'content-digest' => self::header($headers, 'content-digest'),
        ]);

        if (!HttpMessageSignature::verifyEcdsaP256Sha256($base, self::bytes($signature, 'Signature'), $key->getKeyMaterial())) {
            throw new UnauthorizedCommandException('The signature does not verify');
        }

        $this->assertConnectionId($rawBody);
    }

    /**
     * Lower-case names, and repeated lines joined with a comma as RFC 9110 §5.3 does.
     *
     * @param  array<string, string|string[]> $headers
     * @return array<string, string>
     */
    private static function normalizeHeaders(array $headers): array
    {
        $normalized = [];

        foreach ($headers as $name => $value) {
            $name  = strtolower((string) $name);
            $value = is_array($value) ? implode(', ', $value) : (string) $value;

            $normalized[$name] = isset($normalized[$name]) ? $normalized[$name] . ', ' . $value : $value;
        }

        return $normalized;
    }

    /**
     * @param  array<string, string> $headers
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException When the header is missing.
     */
    private static function header(array $headers, string $name): string
    {
        if (!isset($headers[$name]) || '' === trim($headers[$name])) {
            throw new UnauthorizedCommandException(sprintf('The %s header is missing', $name));
        }

        return trim($headers[$name]);
    }

    /**
     * @param array<string, string> $headers
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException
     */
    private static function dictionary(array $headers, string $name): Dictionary
    {
        try {
            return Parser::parseDictionary(self::header($headers, $name));
        } catch (RuntimeException $exception) {
            // ParseException extends it. A value cut off after '=' raises the plain one.
            throw new UnauthorizedCommandException(sprintf('The %s header is not an RFC 8941 dictionary', $name), 0, $exception);
        }
    }

    /**
     * The single member of a dictionary, which must carry this key.
     *
     * @return \gapple\StructuredFields\InnerList|\gapple\StructuredFields\Item
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException
     */
    private static function oneMember(Dictionary $dictionary, string $key)
    {
        $members = iterator_to_array($dictionary);

        if (1 !== count($members) || !isset($members[$key])) {
            throw new UnauthorizedCommandException(sprintf('The header must have exactly one member, %s', $key));
        }

        return $members[$key];
    }

    /**
     * Exactly the four components, in order, each a plain string without parameters.
     *
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException
     */
    private static function assertComponents(InnerList $signatureInput): void
    {
        $names = [];

        foreach ($signatureInput->getValue() as $item) {
            if (!$item instanceof Item || !is_string($item->getValue()) || [] !== iterator_to_array($item->getParameters())) {
                throw new UnauthorizedCommandException('A covered component is not a plain string');
            }

            $names[] = $item->getValue();
        }

        if (self::COMPONENTS !== $names) {
            throw new UnauthorizedCommandException('The covered components are not the ones MyParcel signs');
        }
    }

    /**
     * No tolerance on top of the 60 seconds: a tolerance would also stretch expires.
     *
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException
     */
    private static function assertTime(int $created, int $expires, int $now): void
    {
        if (abs($now - $created) > self::MAX_AGE) {
            throw new UnauthorizedCommandException('The signature was created too far from now');
        }

        if ($expires <= $now) {
            throw new UnauthorizedCommandException('The signature has expired');
        }

        if ($expires - $created > self::MAX_AGE) {
            throw new UnauthorizedCommandException('The signature lives longer than 60 seconds');
        }
    }

    /**
     * One sha-256 member, equal to the hash of the raw body (RFC 9530).
     *
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException
     */
    private static function assertDigest(Dictionary $digest, string $rawBody): void
    {
        $member = self::oneMember($digest, self::DIGEST_ALGORITHM);

        if (!hash_equals(hash('sha256', $rawBody, true), self::bytes($member, 'Content-Digest'))) {
            throw new UnauthorizedCommandException('Content-Digest does not match the body');
        }
    }

    /**
     * The raw bytes of a byte sequence member.
     *
     * @param  \gapple\StructuredFields\InnerList|\gapple\StructuredFields\Item $member
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException
     */
    private static function bytes($member, string $header): string
    {
        // A value between colons, `:base64:`, parses to a Bytes object holding the decoded bytes.
        if (!$member instanceof Item || !$member->getValue() instanceof Bytes) {
            throw new UnauthorizedCommandException(sprintf('The %s value is not a byte sequence', $header));
        }

        return (string) $member->getValue();
    }

    /**
     * The body is a JSON object whose connectionId is this shop's.
     *
     * @throws \MyParcelNL\Sdk\Exception\UnauthorizedCommandException
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function assertConnectionId(string $rawBody): void
    {
        $body = json_decode($rawBody, true);

        if (!is_array($body) || array_is_list($body)) {
            throw new UnauthorizedCommandException('The body is not a JSON object');
        }

        $connectionId = $this->connect->getConnectionId();

        if (null === $connectionId || ($body['connectionId'] ?? null) !== $connectionId) {
            throw new UnauthorizedCommandException('The connectionId is not this shop\'s');
        }
    }
}
