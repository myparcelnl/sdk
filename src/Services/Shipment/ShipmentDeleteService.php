<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Deletes shipments through the Core API client.
 */
final class ShipmentDeleteService
{
    use HasUserAgent;

    private ?Client $api;

    private string $apiKey;

    private ?string $host;

    /**
     * Create the service with an API key, or with a client that keeps its own User-Agent.
     */
    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $host = null
    ) {
        $this->apiKey = $apiKey;
        $this->api    = $api;
        $this->host   = $host;
    }

    /**
     * Delete one or more shipments by id.
     *
     * @param int[] $shipmentIds
     */
    public function deleteMany(array $shipmentIds): void
    {
        if (empty($shipmentIds)) {
            return;
        }

        $ids = implode(';', array_map('intval', $shipmentIds));

        $this->api()->deleteShipments(ids: $ids);
    }

    /**
     * The first request creates the client with the User-Agent of the service, so a proposition that
     * is set after the constructor reaches the API.
     */
    private function api(): Client
    {
        return $this->api ??= ShipmentApiFactory::client($this->apiKey, $this->host, $this->getUserAgentHeader());
    }
}
