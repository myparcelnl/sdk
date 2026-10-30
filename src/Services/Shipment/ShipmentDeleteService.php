<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

/**
 * Deletes shipments through the Jane Core API client.
 *
 * The service adds little to the Jane client, but it keeps the API of v11. So v11 code that calls
 * it keeps working.
 */
final class ShipmentDeleteService
{
    use HasUserAgent;

    private Client $api;

    public function __construct(string $apiKey, ?Client $api = null, ?string $host = null)
    {
        $this->api = $api ?? ShipmentApiFactory::make($apiKey, $host);
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

        $this->api->deleteShipments($ids, ['User-Agent' => $this->getUserAgentHeader()]);
    }
}
