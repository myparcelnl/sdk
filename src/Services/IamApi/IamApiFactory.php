<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\IamApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\IamApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\IamApi\Configuration;

/**
 * Factory for creating the generated IAM API client.
 */
final class IamApiFactory
{
    /**
     * Default HTTP client timeout in seconds.
     *
     * Shared with generated-client based services to ensure consistent behaviour.
     */
    public const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * Create a configured IAM API client.
     *
     * @param string|null $apiKey Optional API key.
     * @param string|null $host Optional IAM API host override.
     * @param string|null $userAgent Optional custom User-Agent.
     */
    public static function make(
        ?string $apiKey = null,
        ?string $host = null,
        ?string $userAgent = null
    ): DefaultApi {
        $resolvedKey = self::resolveApiKey($apiKey);

        if ('' === $resolvedKey) {
            throw new InvalidArgumentException('API key cannot be empty');
        }

        $config = new Configuration();
        $config->setAccessToken(base64_encode($resolvedKey));

        if ($host) {
            $config->setHost($host);
        }

        if ($userAgent) {
            $config->setUserAgent($userAgent);
        }

        $httpOptions = [
            'timeout' => self::DEFAULT_HTTP_TIMEOUT,
            'handler' => self::createHandlerStack(),
            'debug'   => false,
        ];

        if ($host) {
            $httpOptions['base_uri'] = $host;
        }

        return new DefaultApi(new GuzzleClient($httpOptions), $config);
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
