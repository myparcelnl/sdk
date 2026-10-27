<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\MultiCollo;

use InvalidArgumentException;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

final class MultiColloShipmentService
{
    /**
     * Split a shipment into a multi-collo shipment with secondary shipments.
     *
     * Clones the input shipment, reuses (or generates) a shared reference identifier
     * across all colli, and distributes weight evenly across the main and secondary
     * shipments. The returned shipment has secondary_shipments populated and is ready
     * for ShipmentCreateService::create().
     *
     * @param int $amount Total number of collo pieces (including the main shipment).
     */
    public function splitShipment(Shipment $shipment, int $amount): Shipment
    {

        $main = clone $shipment;
        $referenceId = $this->resolveReferenceIdentifier($main->getReferenceIdentifier());
        $main->setReferenceIdentifier($referenceId);
        $carrier = $main->getCarrier();

        $weightPerCollo = $this->resolveWeightPerCollo($main, $amount);
        if (null !== $weightPerCollo) {
            $main->setPhysicalProperties(['weight' => $weightPerCollo]);
        }

        $secondary = ['reference_identifier' => $referenceId];

        if (null !== $carrier) {
            $secondary['carrier'] = $carrier;
        }

        if (null !== $weightPerCollo) {
            $secondary['physical_properties'] = ['weight' => $weightPerCollo];
        }

        $secondaryShipments = array_fill(0, max(0, $amount - 1), $secondary);

        $main->setSecondaryShipments($secondaryShipments);

        return $main;
    }

    /**
     * @param mixed $referenceIdentifier
     */
    private function resolveReferenceIdentifier($referenceIdentifier): string
    {
        if (is_string($referenceIdentifier) && '' !== trim($referenceIdentifier)) {
            return $referenceIdentifier;
        }

        return 'multi_collo_' . uniqid('', true);
    }

    private function resolveWeightPerCollo(Shipment $shipment, int $amount): ?int
    {
        $physicalProperties = $shipment->getPhysicalProperties();
        if (null === $physicalProperties) {
            return null;
        }

        $totalWeight = $physicalProperties->weight ?? 0;
        if ($totalWeight <= 0) {
            return null;
        }

        return (int) floor($totalWeight / $amount);
    }
}
