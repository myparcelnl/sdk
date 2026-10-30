<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Shipment\Concerns;

use MyParcelNL\Sdk\Model\Shipment\Shipment;

/**
 * Gives each shipment without a reference identifier one that starts with `sdk_`, as in v11.
 *
 * ShipmentCreateService and ShipmentPrintService return the created ids as id => reference
 * identifier, so each shipment needs one.
 */
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

