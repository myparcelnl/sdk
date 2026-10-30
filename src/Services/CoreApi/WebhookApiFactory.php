<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;

/**
 * Factory for creating the Jane Core API client that WebhookService uses.
 */
final class WebhookApiFactory
{
    /**
     * Default HTTP client timeout in seconds.
     *
     * Shared with generated-client based services to ensure consistent behaviour.
     */
    public const DEFAULT_HTTP_TIMEOUT = JaneClientFactory::DEFAULT_HTTP_TIMEOUT;

    /**
     * Create a configured Core API client.
     *
     * @param string|null $apiKey Optional API key.
     * @param string|null $host Optional API host override.
     * @param string|null $userAgent Optional custom User-Agent.
     */
    public static function make(
        ?string $apiKey = null,
        ?string $host = null,
        ?string $userAgent = null
    ): Client {
        $resolvedKey = self::resolveApiKey($apiKey);

        if ('' === $resolvedKey) {
            throw new InvalidArgumentException('API key cannot be empty');
        }

        return JaneClientFactory::coreApi($resolvedKey, $host, $userAgent);
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
