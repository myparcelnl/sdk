<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use Firebase\JWT\JWK;
use Firebase\JWT\Key;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use MyParcelNL\Sdk\Exception\CommandCheckUnavailableException;
use MyParcelNL\Sdk\Model\Connect\ConnectConfig;
use MyParcelNL\Sdk\Model\Connect\JwksCache;
use Throwable;

/**
 * Finds the public key MyParcel signed a command with, by the keyid of the signature.
 *
 * The keys come from /.well-known/jwks.json on the same host the connect calls go to, and never
 * from a URL in the request. They are kept in your JwksStorageInterface for an hour. A keyid that
 * is not in the stored set makes the SDK fetch the set again, at most once a minute, because
 * MyParcel rotates keys rarely and a flood of unknown keyids is not a reason to flood MyParcel.
 */
final class JwksProvider
{
    public const JWKS_PATH = '/.well-known/jwks.json';

    /**
     * How long a fetched set is used before it is fetched again, in seconds.
     */
    private const MAX_AGE = 3600;

    /**
     * How long after a fetch an unknown keyid does not cause another one, in seconds.
     */
    private const REFETCH_INTERVAL = 60;

    private const HTTP_TIMEOUT = 10;

    /**
     * @var \MyParcelNL\Sdk\Model\Connect\ConnectConfig
     */
    private $config;

    /**
     * @var \MyParcelNL\Sdk\Services\Connect\JwksStorageInterface
     */
    private $storage;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $http;

    /**
     * @param ConnectConfig        $config  The same config as ConnectService: it picks the host.
     * @param JwksStorageInterface $storage Your own implementation. See {@see JwksStorageInterface}.
     * @param ClientInterface|null $http    The HTTP client the fetch goes out on. Pass one to set a
     *                                      proxy or a CA bundle.
     */
    public function __construct(ConnectConfig $config, JwksStorageInterface $storage, ?ClientInterface $http = null)
    {
        $this->config  = $config;
        $this->storage = $storage;
        $this->http    = $http ?? new GuzzleClient(['timeout' => self::HTTP_TIMEOUT]);
    }

    /**
     * Find the key with this kid, fetching the set when it is stale or does not have the kid.
     *
     * @param  string $kid The keyid parameter of the signature.
     * @return \Firebase\JWT\Key|null Null when MyParcel has no such key, or the key is not an
     *                                ES256 key on P-256. Reply 401.
     * @throws \MyParcelNL\Sdk\Exception\CommandCheckUnavailableException When the set has to be
     *                                                                     fetched and that fails.
     *                                                                     Reply 503.
     */
    public function findKey(string $kid): ?Key
    {
        $now   = time();
        $cache = $this->storage->loadJwks();

        // A fetch time in the future, from a clock that stepped back or a tampered row, would make
        // the set fresh forever. So it counts as stale.
        if (null === $cache || !self::isYoungerThan($cache, $now, self::MAX_AGE)) {
            $cache = $this->fetch($now);
        }

        $key = self::keyIn($cache, $kid);

        if (null !== $key || self::isYoungerThan($cache, $now, self::REFETCH_INTERVAL)) {
            return $key;
        }

        return self::keyIn($this->fetch($now), $kid);
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\CommandCheckUnavailableException
     */
    private function fetch(int $now): JwksCache
    {
        $url = $this->config->getHost() . self::JWKS_PATH;

        try {
            // No redirects: the keys come from this one URL and nowhere else.
            $response = $this->http->request('GET', $url, [
                'headers'         => ['Accept' => 'application/json'],
                'allow_redirects' => false,
            ]);
            $jwks     = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw new CommandCheckUnavailableException(
                sprintf('Could not fetch the MyParcel keys from %s: %s', $url, $exception->getMessage()),
                0,
                $exception
            );
        }

        if (!is_array($jwks) || !isset($jwks['keys']) || !is_array($jwks['keys'])) {
            throw new CommandCheckUnavailableException(sprintf('The MyParcel keys at %s are not a JWK Set', $url));
        }

        $cache = new JwksCache($jwks, $now);

        // A set without one usable key is a broken answer, not a key rotation. Keeping it would
        // turn every command into a 401 for an hour, and MyParcel does not resend after a 401.
        if ([] === array_filter($jwks['keys'], self::isUsableKey(...))) {
            throw new CommandCheckUnavailableException(sprintf('The MyParcel keys at %s hold no ES256 key on P-256', $url));
        }

        $this->storage->saveJwks($cache);

        return $cache;
    }

    private static function isYoungerThan(JwksCache $cache, int $now, int $seconds): bool
    {
        $age = $now - $cache->getFetchedAt();

        return $age >= 0 && $age < $seconds;
    }

    /**
     * @param mixed $jwk One entry of the keys list, as decoded.
     */
    private static function isUsableKey($jwk): bool
    {
        return is_array($jwk)
            && 'EC' === ($jwk['kty'] ?? null)
            && 'P-256' === ($jwk['crv'] ?? null)
            && 'ES256' === ($jwk['alg'] ?? null);
    }

    /**
     * The key with this kid, when it is an ES256 key on P-256. Anything else is as good as absent.
     */
    private static function keyIn(JwksCache $cache, string $kid): ?Key
    {
        foreach ($cache->getJwks()['keys'] as $jwk) {
            if (!is_array($jwk) || ($jwk['kid'] ?? null) !== $kid) {
                continue;
            }

            if (!self::isUsableKey($jwk)) {
                return null;
            }

            try {
                // firebase/php-jwt turns x and y into a PEM public key, in Key::getKeyMaterial().
                return JWK::parseKey($jwk);
            } catch (Throwable) {
                // Missing or malformed coordinates: no key to verify with.
                return null;
            }
        }

        return null;
    }
}
