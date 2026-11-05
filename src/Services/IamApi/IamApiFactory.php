<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\IamApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Factory for creating the generated IAM API client.
 */
final class IamApiFactory
{
    /**
     * Idle timeout in seconds of the HTTP client that this factory creates, the same value as
     * JaneClientFactory::DEFAULT_HTTP_TIMEOUT.
     */
    public const DEFAULT_HTTP_TIMEOUT = JaneClientFactory::DEFAULT_HTTP_TIMEOUT;

    /**
     * Create a configured IAM API client.
     *
     * @param string|null              $apiKey     Optional API key. Without one, the factory reads `API_KEY`, `API_KEY_NL` or `API_KEY_BE` from the environment.
     * @param string|null              $host       Optional IAM API host override.
     * @param string|null              $userAgent  Optional custom User-Agent.
     * @param array<string, string>    $headers    Sent on every request.
     * @param HttpClientInterface|null $httpClient Sends the requests, with its own options such as the timeout.
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

        return JaneClientFactory::iamApi($resolvedKey, $host, $userAgent, $headers, $httpClient);
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
