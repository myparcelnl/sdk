<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Factory for creating the Jane Core API client that WebhookService uses.
 *
 * It builds the same client as ShipmentApiFactory. It stays next to it because v11 code calls
 * WebhookApiFactory::make().
 */
final class WebhookApiFactory
{
    /**
     * The idle timeout of JaneClientFactory in seconds, kept here because v11 code reads this constant.
     */
    public const DEFAULT_HTTP_TIMEOUT = JaneClientFactory::DEFAULT_HTTP_TIMEOUT;

    /**
     * Create a configured Core API client.
     *
     * @param string|null              $apiKey     Optional API key.
     * @param string|null              $host       Optional API host override.
     * @param string|null              $userAgent  Optional custom User-Agent.
     * @param array<string, string>    $headers    Sent on every request, such as `x-dmp-*` API feature flags.
     * @param HttpClientInterface|null $httpClient Sends the requests, with its own options such as the timeout. Tests pass a MockHttpClient.
     */
    public static function make(
        ?string $apiKey = null,
        ?string $host = null,
        ?string $userAgent = null,
        array $headers = [],
        ?HttpClientInterface $httpClient = null
    ): Client {
        $resolvedKey = self::resolveApiKey($apiKey);

        if ('' === $resolvedKey) {
            throw new InvalidArgumentException('API key cannot be empty');
        }

        return JaneClientFactory::coreApi($resolvedKey, $host, $userAgent, $headers, $httpClient);
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
