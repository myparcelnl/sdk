<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesPriceEuro;
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
                'package_type' => RefShipmentPackageType::MAILBOX,
            ],
        ]);

        $options = $shipment->getOptions();

        $this->assertInstanceOf(ShipmentOptions::class, $options);
        $this->assertIsInt($options->getPackageType());
        $this->assertSame((int) RefShipmentPackageType::MAILBOX, $options->getPackageType());
    }

    public function testGetCarrierLazilyNormalizesSdkNameToId(): void
    {
        $shipment = new Shipment([
            'carrier' => RefTypesCarrierV2::POSTNL,
        ]);

        $carrier = $shipment->getCarrier();

        $this->assertIsInt($carrier);
        $this->assertSame((int) RefTypesCarrier::POSTNL, $carrier);
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

    public function testShipmentOptionsFromOrderResponseHydratesSharedOptionsContract(): void
    {
        $options = ShipmentOptions::fromOrderResponse([
            'carrier_id'       => 1,
            'shipment_options' => [
                'date'              => '2026-04-17',
                'delivery_type'     => RefTypesDeliveryTypeV2::STANDARD,
                'package_type'      => RefShipmentPackageTypeV2::PACKAGE,
                'signature'         => true,
                'collect'           => false,
                'receipt_code'      => true,
                'only_recipient'    => true,
                'age_check'         => false,
                'large_format'      => true,
                'return'            => false,
                'priority_delivery' => true,
                'label_description' => 'Test label',
                'insurance'         => [
                    'amount'   => 500,
                    'currency' => 'EUR',
                ],
            ],
        ]);

        $this->assertSame('2026-04-17', $options->getDeliveryDate());
        $this->assertSame(RefTypesDeliveryType::STANDARD, $options->getDeliveryType());
        $this->assertSame(RefShipmentPackageType::PACKAGE, $options->getPackageType());
        $this->assertSame(1, $options->getSignature());
        $this->assertSame(0, $options->getCollect());
        $this->assertSame(1, $options->getReceiptCode());
        $this->assertSame(1, $options->getOnlyRecipient());
        $this->assertSame(0, $options->getAgeCheck());
        $this->assertSame(1, $options->getLargeFormat());
        $this->assertSame(0, $options->getReturn());
        $this->assertSame(1, $options->getPriorityDelivery());
        $this->assertSame('Test label', $options->getLabelDescription());
        $this->assertInstanceOf(RefTypesPriceEuro::class, $options->getInsurance());
        $this->assertSame(500, $options->getInsurance()->getAmount());
    }

    public function testShipmentOptionsSetDeliveryTypeAcceptsSdkEnumAndStoresId(): void
    {
        $options = (new ShipmentOptions())
            ->setDeliveryType(RefTypesDeliveryTypeV2::MORNING);

        $this->assertSame(RefTypesDeliveryType::MORNING, $options->getDeliveryType());
    }

    public function testShipmentOptionsSetDeliveryTypeAcceptsNumericString(): void
    {
        $options = (new ShipmentOptions())
            ->setDeliveryType('2');

        $this->assertSame(2, $options->getDeliveryType());
    }

    public function testShipmentOptionsSetDeliveryTypeThrowsOnUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown delivery type 'NONEXISTENT'");

        (new ShipmentOptions())->setDeliveryType('NONEXISTENT');
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
