<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use Jane\Component\OpenApiRuntime\Client\AuthenticationPlugin;
use Jane\Component\OpenApiRuntime\Client\Plugin\AuthenticationRegistry;
use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Authentication\BearerAuthentication as CoreApiAuthentication;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer as CoreApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\FormEncoder as CoreApiFormEncoder;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Builds the generated Jane clients with the API key, the user agent and extra headers.
 *
 * @internal Use the API factories, such as ShipmentApiFactory.
 */
final class JaneClientFactory
{
    /**
     * The Core API host from the spec.
     */
    public const CORE_API_HOST = 'https://api.myparcel.nl';

    /**
     * Idle timeout in seconds of the HTTP client that this factory creates: a request stops when no
     * data arrives for this time.
     */
    public const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * Create a Core API client.
     *
     * @param string                   $apiKey     The API key as the Backoffice shows it. This method encodes it.
     * @param string|null              $host       Overrides the Core API host. An empty string uses the default.
     * @param string|null              $userAgent  Sent as the User-Agent on every request.
     * @param array<string, string>    $headers    Sent on every request, such as `x-dmp-*` API feature flags.
     * @param HttpClientInterface|null $httpClient Sends the requests, with its own options such as the timeout.
     */
    public static function coreApi(
        string $apiKey,
        ?string $host = null,
        ?string $userAgent = null,
        array $headers = [],
        ?HttpClientInterface $httpClient = null
    ): CoreApiClient {
        return new CoreApiClient(
            self::createHttpClient(
                $host ?: self::CORE_API_HOST,
                new CoreApiAuthentication(base64_encode($apiKey)),
                $userAgent,
                $headers,
                $httpClient
            ),
            self::createSerializer(new CoreApiNormalizer(), new CoreApiFormEncoder())
        );
    }

    /**
     * Add the timeout, the headers, the host and the authentication to the HTTP client.
     *
     * @param array<string, string> $headers
     */
    private static function createHttpClient(
        string $host,
        AuthenticationPlugin $authentication,
        ?string $userAgent,
        array $headers,
        ?HttpClientInterface $httpClient
    ): HttpClientInterface {
        if ($userAgent) {
            $headers['User-Agent'] = $userAgent;
        }

        // The timeout applies only to the client that this factory creates. A client that the caller
        // passes keeps its own options.
        $httpClient = ($httpClient ?? HttpClient::create(['timeout' => self::DEFAULT_HTTP_TIMEOUT]))
            ->withOptions(['headers' => $headers]);

        // The same order as the generated Client::create() with the AuthenticationRegistry in
        // `$additionalPlugins`. The registry adds the API key only to operations that the spec secures.
        $httpClient = (new ServerUrlHttpClient($host))($httpClient);

        return (new AuthenticationRegistry([$authentication]))($httpClient);
    }

    /**
     * Build the serializer of the generated Client::create() with two differences, both workarounds.
     */
    private static function createSerializer(
        NormalizerInterface&DenormalizerInterface $normalizer,
        EncoderInterface&DecoderInterface $formEncoder
    ): SkipValidationOnReadSerializer {
        // Workaround 1, validation on requests only. Client::create() builds a serializer that also
        // validates responses, and a client cannot change it later. So this factory builds the same
        // parts, wrapped in SkipValidationOnReadSerializer.
        // @todo janephp/janephp#1147: #707 pins a Jane commit with janephp/janephp#1151, sets
        //       'validation' => ValidationOption::NormalizeOnly, and removes the wrapper.
        //
        // Workaround 2, the option flags. The spec types a flag such as `signature` as a number, so
        // Jane makes it a float, and the API expects the integers 0 and 1. Client::create() keeps
        // JSON_PRESERVE_ZERO_FRACTION on, so the flag goes out as 1.0. This encoder turns it off:
        // every float with a zero fraction goes out as an integer. No request body of the Core API
        // or the IAM API has a number that is a real float, so nothing else changes.
        // @todo CLOUD-2372: use the JsonEncode options of Client::create() when the spec types the
        //       flags as integer (mypadev/core-api#5263), and the client is generated again.
        return new SkipValidationOnReadSerializer(
            new Serializer(
                [new ArrayDenormalizer(), $normalizer],
                [new JsonEncoder(new JsonEncode([JsonEncode::OPTIONS => 0]), new JsonDecode([JsonDecode::ASSOCIATIVE => true])), $formEncoder]
            )
        );
    }
}
