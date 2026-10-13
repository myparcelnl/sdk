<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\MultiCollo;

use InvalidArgumentException;
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
        self::assertSame(300, (int) $main->getPhysicalProperties()->getWeight());
        self::assertSame('order-1001', (string) $main->getReferenceIdentifier());

        $secondaryOne = $main->getSecondaryShipments()[0];
        self::assertSame('order-1001', (string) $secondaryOne->getReferenceIdentifier());
        self::assertSame(1, (int) $secondaryOne->getCarrier());
        self::assertSame(300, (int) $secondaryOne->getPhysicalProperties()->getWeight());
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
        self::assertSame($referenceIdentifier, (string) $main->getSecondaryShipments()[0]->getReferenceIdentifier());
    }
}

