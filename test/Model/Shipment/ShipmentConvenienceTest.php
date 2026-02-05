<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Model\Shipment\Carrier;
use MyParcelNL\Sdk\Model\Shipment\PackageType;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use PHPUnit\Framework\TestCase;

final class ShipmentConvenienceTest extends TestCase
{
    public function testWithCarrierSetsCarrierId(): void
    {
        $shipment = (new Shipment())
            ->withCarrier(Carrier::POSTNL);

        $this->assertSame(RefTypesCarrier::_1, $shipment->getCarrier());
    }

    public function testWithPackageTypeCreatesOptionsIfMissingAndSetsId(): void
    {
        $shipment = (new Shipment())
            ->withPackageType(PackageType::MAILBOX);

        $this->assertNotNull($shipment->getOptions());
        $this->assertSame(RefShipmentPackageType::_2, $shipment->getOptions()->getPackageType());
    }

    public function testCarrierToIdThrowsOnUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown carrier 'FOO'");

        Carrier::toId('FOO');
    }

    public function testPackageTypeToIdThrowsOnUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown package type 'FOO'");

        PackageType::toId('FOO');
    }
}
