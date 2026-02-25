<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;
use MyParcelNL\Sdk\Model\Shipment\Carrier;
use MyParcelNL\Sdk\Model\Shipment\PackageType;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Model\Shipment\ShipmentOptions;
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

    public function testGetRecipientLazilyNormalizesArrayData(): void
    {
        $shipment = new Shipment([
            'recipient' => [
                'cc' => 'NL',
            ],
        ]);

        $recipient = $shipment->getRecipient();

        $this->assertInstanceOf(ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient::class, $recipient);
        $this->assertSame('NL', $recipient->getCc());
    }

    public function testGetPhysicalPropertiesLazilyNormalizesArrayData(): void
    {
        $shipment = new Shipment([
            'physical_properties' => [
                'weight' => 1234,
            ],
        ]);

        $physicalProperties = $shipment->getPhysicalProperties();

        $this->assertInstanceOf(
            ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties::class,
            $physicalProperties
        );
        $this->assertSame(1234, $physicalProperties->getWeight());
    }

    public function testGetOptionsLazilyNormalizesArrayData(): void
    {
        $shipment = new Shipment([
            'options' => [
                'package_type' => RefShipmentPackageType::_2,
            ],
        ]);

        $options = $shipment->getOptions();

        $this->assertInstanceOf(ShipmentOptions::class, $options);
        $this->assertIsInt($options->getPackageType());
        $this->assertSame((int) RefShipmentPackageType::_2, $options->getPackageType());
    }

    public function testGetCarrierLazilyNormalizesSdkNameToId(): void
    {
        $shipment = new Shipment([
            'carrier' => RefTypesCarrierV2::POSTNL,
        ]);

        $carrier = $shipment->getCarrier();

        $this->assertIsInt($carrier);
        $this->assertSame((int) RefTypesCarrier::_1, $carrier);
    }

    public function testSetCarrierAcceptsSdkCarrierNameAndStoresId(): void
    {
        $shipment = (new Shipment())
            ->setCarrier(RefTypesCarrierV2::POSTNL);

        $this->assertSame(RefTypesCarrier::POSTNL, $shipment->getCarrier());
    }

    public function testWithPackageTypeCreatesOptionsIfMissingAndSetsId(): void
    {
        $shipment = (new Shipment())
            ->withPackageType(RefShipmentPackageTypeV2::MAILBOX);

        $this->assertNotNull($shipment->getOptions());
        $this->assertSame(RefShipmentPackageType::MAILBOX, $shipment->getOptions()->getPackageType());
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
