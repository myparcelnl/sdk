<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Factory for creating the Jane Core API client for the webhook endpoints. It returns the client
 * of `ShipmentApiFactory`, with the same API key rules.
 */
final class WebhookApiFactory
{
    /**
     * Create a configured Core API client.
     *
     * @param string|null              $apiKey     Optional API key.
     * @param string|null              $host       Optional API host override.
     * @param string|null              $userAgent  Optional custom User-Agent.
     * @param array<string, string>    $headers    Sent on every request, such as `x-dmp-*` API feature flags.
     * @param HttpClientInterface|null $httpClient Sends the requests, with its own options such as the timeout.
     */
    public static function make(
        ?string $apiKey = null,
        ?string $host = null,
        ?string $userAgent = null,
        array $headers = [],
        ?HttpClientInterface $httpClient = null
    ): Client {
        return ShipmentApiFactory::make($apiKey, $host, $userAgent, $headers, $httpClient);
    }
}
