<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\MultiCollo;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\SecondaryShipmentRequest;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerPhysicalProperties;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

final class MultiColloShipmentService
{
    /**
     * Convert one shipment into a multi-collo shipment with secondary shipments.
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

        $secondaryShipments = [];
        for ($i = 1; $i < $amount; $i++) {
            $secondary = new SecondaryShipmentRequest();
            $secondary->setReferenceIdentifier($referenceId);

            if (null !== $carrier) {
                $secondary->setCarrier($carrier);
            }

            if (null !== $weightPerCollo) {
                $secondaryPhysicalProperties = new ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerPhysicalProperties();
                $secondaryPhysicalProperties->setWeight($weightPerCollo);
                $secondary->setPhysicalProperties($secondaryPhysicalProperties);
            }

            $secondaryShipments[] = $secondary;
        }

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

        $totalWeight = (int) $physicalProperties->getWeight();
        if ($totalWeight <= 0) {
            return null;
        }

        return (int) floor($totalWeight / $amount);
    }
}
