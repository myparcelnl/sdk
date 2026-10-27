<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\MultiCollo;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\MultiCollo\MultiColloShipmentService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class MultiColloShipmentServiceTest extends TestCase
{
    public function testSplitShipmentCreatesSecondaryShipmentsAndDistributesWeight(): void
    {
        $shipment = (new Shipment())
            ->setReferenceIdentifier('order-1001')
            ->setCarrier(1)
            ->setPhysicalProperties(['weight' => 900]);

        $service = new MultiColloShipmentService();
        $main = $service->splitShipment($shipment, 3);

        self::assertCount(2, $main->getSecondaryShipments());
        self::assertSame(300, $main->getPhysicalProperties()->weight);
        self::assertSame('order-1001', (string) $main->getReferenceIdentifier());

        $secondaryOne = $main->getSecondaryShipments()[0];
        self::assertSame('order-1001', $secondaryOne->referenceIdentifier);
        self::assertSame(RefTypesCarrier::Postnl, $secondaryOne->carrier);
        self::assertSame(300, $secondaryOne->physicalProperties->weight);
    }

    public function testSplitShipmentDoesNotChangeTheShipmentItGets(): void
    {
        $shipment = (new Shipment())
            ->setReferenceIdentifier('order-1001')
            ->setCarrier(1)
            ->setPhysicalProperties(['weight' => 900]);

        (new MultiColloShipmentService())->splitShipment($shipment, 3);

        self::assertNull($shipment->getSecondaryShipments());
        self::assertSame(900, $shipment->getPhysicalProperties()->weight);
    }

    public function testSplitShipmentGeneratesReferenceIdentifierWhenMissing(): void
    {
        $shipment = (new Shipment())
            ->setCarrier(1)
            ->setPhysicalProperties(['weight' => 600]);

        $service = new MultiColloShipmentService();
        $main = $service->splitShipment($shipment, 2);

        $referenceIdentifier = (string) $main->getReferenceIdentifier();
        self::assertStringStartsWith('multi_collo_', $referenceIdentifier);
        self::assertSame($referenceIdentifier, $main->getSecondaryShipments()[0]->referenceIdentifier);
    }
}

