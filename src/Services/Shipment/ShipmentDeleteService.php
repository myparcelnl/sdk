<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;

final class ShipmentDeleteService
{
    use HasUserAgent;

    private ShipmentApi $api;

    public function __construct(string $apiKey, ?ShipmentApi $api = null, ?string $host = null)
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

        $this->api->deleteShipments($ids, $this->getUserAgentHeader());
    }
}
