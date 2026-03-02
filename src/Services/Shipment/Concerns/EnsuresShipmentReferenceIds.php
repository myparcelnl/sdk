<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment\Concerns;

use MyParcelNL\Sdk\Model\Shipment\Shipment;

trait EnsuresShipmentReferenceIds
{
    /**
     * @param Shipment[] $shipments
     */
    protected function ensureReferenceIds(array $shipments): void
    {
        foreach ($shipments as $shipment) {
            if (! $shipment->getReferenceIdentifier()) {
                $shipment->setReferenceIdentifier('sdk_' . uniqid('', true));
            }
        }
    }
}

