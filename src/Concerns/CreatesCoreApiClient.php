<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Concerns;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Gives a service the Core API client that it was given, or one that it creates on the first request.
 */
trait CreatesCoreApiClient
{
    use HasUserAgent;

    private ?Client $api;

    private ?string $apiKey;

    private ?string $host;

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     *
     * @param string|null $apiKey The API key of the client that the first request creates. Without one,
     *                            ShipmentApiFactory reads `API_KEY`, `API_KEY_NL` or `API_KEY_BE`.
     * @param Client|null $api    A client with its own API key, host, headers or User-Agent, such as one
     *                            from ShipmentApiFactory. With a client, `$apiKey` and `$host` are not used.
     * @param string|null $host   The host of the client that the first request creates.
     */
    public function __construct(?string $apiKey = null, ?Client $api = null, ?string $host = null)
    {
        $this->apiKey = $apiKey;
        $this->api    = $api;
        $this->host   = $host;
    }

    /**
     * The first request creates the client with the User-Agent of the service, so a proposition that
     * is set after the constructor reaches the API.
     */
    private function api(): Client
    {
        return $this->api ??= ShipmentApiFactory::client($this->apiKey, $this->host, $this->getUserAgentHeader());
    }

    /**
     * Join IDs as the `;`-separated list that the Core API takes in a path, such as `1;2`.
     *
     * @param int[] $ids
     */
    private static function idList(array $ids): string
    {
        return implode(';', array_map('intval', $ids));
    }
}
