<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\MultiCollo;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem as SecondaryShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalProperties as SecondaryPhysicalProperties;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;

/**
 * Splits a shipment into a multi-collo shipment.
 */
final class MultiColloShipmentService
{
    /**
     * Split a shipment into a main shipment with secondary shipments.
     *
     * The method returns a copy of the shipment, so the shipment itself does not change. The copy
     * and its secondary shipments share one reference identifier: the one of the shipment, or a
     * new one that starts with `multi_collo_`. The weight is divided over the main and the
     * secondary shipments. The copy is ready for ShipmentCreateService::create().
     *
     * @param int $amount The number of colli, including the main shipment.
     */
    public function splitShipment(ShipmentItem $shipment, int $amount): ShipmentItem
    {
        $main                      = CoreApiModels::copy($shipment);
        $referenceId               = $this->resolveReferenceIdentifier($main->referenceIdentifier ?? null);
        $main->referenceIdentifier = $referenceId;
        $weightPerCollo            = $this->resolveWeightPerCollo($main, $amount);

        if (null !== $weightPerCollo) {
            $main->physicalProperties->weight = $weightPerCollo;
        }

        $main->secondaryShipments = [];

        for ($i = 1; $i < $amount; $i++) {
            $main->secondaryShipments[] = $this->secondaryShipment($main, $referenceId, $weightPerCollo);
        }

        return $main;
    }

    private function secondaryShipment(ShipmentItem $main, string $referenceId, ?int $weightPerCollo): SecondaryShipment
    {
        $secondary                      = new SecondaryShipment();
        $secondary->referenceIdentifier = $referenceId;

        if (isset($main->carrier)) {
            $secondary->carrier = $main->carrier;
        }

        if (null !== $weightPerCollo) {
            $secondary->physicalProperties         = new SecondaryPhysicalProperties();
            $secondary->physicalProperties->weight = $weightPerCollo;
        }

        return $secondary;
    }

    private function resolveReferenceIdentifier(mixed $referenceIdentifier): string
    {
        // A reference identifier can be an int. Only an empty one gets a generated one.
        if (null !== $referenceIdentifier && '' !== trim((string) $referenceIdentifier)) {
            return (string) $referenceIdentifier;
        }

        return 'multi_collo_' . uniqid('', true);
    }

    private function resolveWeightPerCollo(ShipmentItem $shipment, int $amount): ?int
    {
        $totalWeight = $shipment->physicalProperties->weight ?? 0;

        if ($totalWeight <= 0) {
            return null;
        }

        return (int) floor($totalWeight / $amount);
    }
}
