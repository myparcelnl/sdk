<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\MultiCollo;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem as SecondaryShipment;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\MultiCollo\MultiColloShipmentService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class MultiColloShipmentServiceTest extends TestCase
{
    public function testSplitShipmentCreatesSecondaryShipmentsAndDistributesWeight(): void
    {
        $main = (new MultiColloShipmentService())->splitShipment(self::shipment('order-1001', 900), 3);

        self::assertCount(2, $main->secondaryShipments);
        self::assertSame(300, $main->physicalProperties->weight);
        self::assertSame('order-1001', $main->referenceIdentifier);

        $secondaryOne = $main->secondaryShipments[0];
        self::assertInstanceOf(SecondaryShipment::class, $secondaryOne);
        self::assertSame('order-1001', $secondaryOne->referenceIdentifier);
        self::assertSame(RefTypesCarrier::Postnl, $secondaryOne->carrier);
        self::assertSame(300, $secondaryOne->physicalProperties->weight);
    }

    public function testSplitShipmentDoesNotChangeTheShipmentItGets(): void
    {
        $shipment = self::shipment('order-1001', 900);

        (new MultiColloShipmentService())->splitShipment($shipment, 3);

        self::assertFalse(isset($shipment->secondaryShipments));
        self::assertSame(900, $shipment->physicalProperties->weight);
    }

    public function testSplitShipmentKeepsAnIntReferenceIdentifierAsAString(): void
    {
        $main = (new MultiColloShipmentService())->splitShipment(self::shipment(12345, 600), 2);

        self::assertSame('12345', $main->referenceIdentifier);
        self::assertSame('12345', $main->secondaryShipments[0]->referenceIdentifier);
    }

    public function testSplitShipmentGeneratesReferenceIdentifierWhenMissing(): void
    {
        $main = (new MultiColloShipmentService())->splitShipment(self::shipment(null, 600), 2);

        self::assertStringStartsWith('multi_collo_', $main->referenceIdentifier);
        self::assertSame($main->referenceIdentifier, $main->secondaryShipments[0]->referenceIdentifier);
    }

    public function testSplitShipmentWithoutWeightSetsNoPhysicalProperties(): void
    {
        $main = (new MultiColloShipmentService())->splitShipment(self::shipment('order-1', null), 2);

        self::assertFalse(isset($main->physicalProperties));
        self::assertFalse(isset($main->secondaryShipments[0]->physicalProperties));
    }

    private static function shipment(int|string|null $referenceIdentifier, ?int $weight): ShipmentItem
    {
        return CoreApiModels::toModel(array_filter([
            'reference_identifier' => $referenceIdentifier,
            'carrier'              => 1,
            'physical_properties'  => null === $weight ? null : ['weight' => $weight],
        ], static fn (mixed $value): bool => null !== $value), ShipmentItem::class);
    }
}
