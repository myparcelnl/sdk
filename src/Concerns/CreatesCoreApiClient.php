<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Concerns;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Gives a service the Core API client that it was given, or one that it creates on the first request.
 *
 * The service uses HasUserAgent too.
 */
trait CreatesCoreApiClient
{
    private ?Client $api;

    private string $apiKey;

    private ?string $host;

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     */
    public function __construct(string $apiKey, ?Client $api = null, ?string $host = null)
    {
        $this->apiKey = $apiKey;
        $this->api    = $api;
        $this->host   = $host;
    }

    abstract public function getUserAgentHeader(): string;

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
