<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment;

use MyParcelNL\Sdk\Concerns\CreatesCoreApiClient;

/**
 * Deletes shipments through the Core API client.
 */
final class ShipmentDeleteService
{
    use CreatesCoreApiClient;

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

        $ids = self::idList($shipmentIds);

        $this->api()->deleteShipments(ids: $ids);
    }
}
