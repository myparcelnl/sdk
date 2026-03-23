<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration;

/**
 * Factory for creating the generated ShipmentApi client.
 */
final class ShipmentApiFactory
{
    /**
     * Default HTTP client timeout in seconds.
     *
     * Shared across all shipment services to ensure consistent behaviour.
     * The MyParcel API typically responds within 2-3 seconds; 10 seconds
     * provides headroom for label generation and large batch queries.
     */
    public const DEFAULT_HTTP_TIMEOUT = 10;
    /**
     * Create a configured ShipmentApi client.
     *
     * @param string|null $apiKey Optional API key.
     * @param string|null $host Optional API host override.
     * @param string|null $userAgent Optional custom User-Agent.
     */
    public static function make(
        ?string $apiKey = null,
        ?string $host = null,
        ?string $userAgent = null
    ): ShipmentApi {
        $resolvedKey = self::resolveApiKey($apiKey);

        if ('' === $resolvedKey) {
            throw new \InvalidArgumentException('API key cannot be empty');
        }

        $encoded = base64_encode($resolvedKey);

        $config = new Configuration();
        $config->setAccessToken($encoded);

        if ($host) {
            $config->setHost($host);
        }

        if ($userAgent) {
            $config->setUserAgent($userAgent);
        }

        $stack = self::createHandlerStack();

        $httpOptions = [
            'timeout'  => self::DEFAULT_HTTP_TIMEOUT,
            'handler'  => $stack,
            'debug'    => false,
        ];

        if ($host) {
            $httpOptions['base_uri'] = $host;
        }

        $http = new GuzzleClient($httpOptions);

        return new ShipmentApi($http, $config);
    }

    private static function createHandlerStack(): HandlerStack
    {
        return HandlerStack::create();
    }

    /**
     * Resolve the API key from arguments or environment variables.
     *
     * Resolution order:
     * 1. Explicit argument
     * 2. Unified API_KEY
     * 3. Legacy API_KEY_NL / API_KEY_BE (backward compatibility)
     */
    private static function resolveApiKey(?string $apiKey): string
    {
        if (null !== $apiKey && '' !== $apiKey) {
            return $apiKey;
        }

        $unifiedKey = getenv('API_KEY');
        if ($unifiedKey && '' !== $unifiedKey) {
            return $unifiedKey;
        }

        $legacyKey = getenv('API_KEY_NL') ?: getenv('API_KEY_BE');
        if ($legacyKey && '' !== $legacyKey) {
            return $legacyKey;
        }

        return '';
    }

}
