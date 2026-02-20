<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Model\Shipment\Carrier;
use MyParcelNL\Sdk\Model\Shipment\PackageType;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use PHPUnit\Framework\TestCase;

final class ShipmentConvenienceTest extends TestCase
{
    public function testWithRecipientCountryCodeSetsRecipientCc(): void
    {
        $shipment = (new Shipment())
            ->withRecipientCountryCode('NL');

        $this->assertNotNull($shipment->getRecipient());
        $this->assertSame('NL', $shipment->getRecipient()->getCc());
    }

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

    public function testWithWeightSetsWeight(): void
    {
        $shipment = (new Shipment())
            ->withWeight(500);

        $this->assertNotNull($shipment->getPhysicalProperties());
        $this->assertSame(500, $shipment->getPhysicalProperties()->getWeight());
    }

    public function testWithWeightRejectsFloatWhenStrictTypesEnabled(): void
    {
        $this->expectException(\TypeError::class);

        (new Shipment())->withWeight(500.5);
    }

    public function testCarrierToApiRefThrowsOnUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown carrier 'FOO'");

        Carrier::toApiRef('FOO');
    }

    public function testPackageTypeToApiRefThrowsOnUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown package type 'FOO'");

        PackageType::toApiRef('FOO');
    }
}
