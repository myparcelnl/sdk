<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesPriceEuro;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient;
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
            ->setRecipient(['city' => 'Hoofddorp'])
            ->withRecipientCountryCode('NL');

        $this->assertSame('NL', $shipment->getRecipient()->cc);
        $this->assertSame('Hoofddorp', $shipment->getRecipient()->city);
    }

    public function testSetRecipientTurnsAnArrayIntoTheModel(): void
    {
        $shipment = new Shipment([
            'recipient' => [
                'cc'          => 'NL',
                'postal_code' => '2132JE',
            ],
        ]);

        $recipient = $shipment->getRecipient();

        $this->assertInstanceOf(ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class, $recipient);
        $this->assertSame('NL', $recipient->cc);
        $this->assertSame('2132JE', $recipient->postalCode);
    }

    public function testSetPhysicalPropertiesTurnsAnArrayIntoTheModel(): void
    {
        $shipment = new Shipment([
            'physical_properties' => [
                'weight' => 1234,
            ],
        ]);

        $physicalProperties = $shipment->getPhysicalProperties();

        $this->assertInstanceOf(ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalProperties::class, $physicalProperties);
        $this->assertSame(1234, $physicalProperties->weight);
    }

    public function testSetOptionsTurnsAnArrayIntoShipmentOptions(): void
    {
        $shipment = new Shipment([
            'options' => [
                'package_type' => RefShipmentPackageType::Mailbox,
            ],
        ]);

        $options = $shipment->getOptions();

        $this->assertInstanceOf(ShipmentOptions::class, $options);
        $this->assertSame(RefShipmentPackageType::Mailbox->value, $options->getPackageType());
    }

    /**
     * @dataProvider provideCarriers
     */
    public function testSetCarrierStoresTheId(mixed $carrier): void
    {
        $shipment = (new Shipment())->setCarrier($carrier);

        $this->assertSame(RefTypesCarrier::Postnl->value, $shipment->getCarrier());
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function provideCarriers(): array
    {
        return [
            'id'             => [1],
            'numeric string' => ['1'],
            'v2 name'        => ['POSTNL'],
            'v2 enum case'   => [RefCapabilitiesSharedCarrierV2::Postnl],
            'id enum case'   => [RefTypesCarrier::Postnl],
        ];
    }

    public function testSetCarrierThrowsOnUnknownName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown carrier 'FOO'");

        (new Shipment())->setCarrier('FOO');
    }

    public function testWithPackageTypeCreatesOptionsIfMissingAndSetsId(): void
    {
        $shipment = (new Shipment())
            ->withPackageType(RefShipmentPackageTypeV2::Mailbox);

        $this->assertNotNull($shipment->getOptions());
        $this->assertSame(RefShipmentPackageType::Mailbox->value, $shipment->getOptions()->getPackageType());
    }

    public function testWithWeightSetsWeight(): void
    {
        $shipment = (new Shipment())
            ->setPhysicalProperties(['height' => 10])
            ->withWeight(500);

        $this->assertSame(500, $shipment->getPhysicalProperties()->weight);
        $this->assertSame(10, $shipment->getPhysicalProperties()->height);
    }

    public function testWithWeightRejectsFloatWhenStrictTypesEnabled(): void
    {
        $this->expectException(\TypeError::class);

        (new Shipment())->withWeight(500.5);
    }

    public function testTheConstructorThrowsOnAnUnknownField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown shipment field 'recipent'");

        new Shipment(['recipent' => ['cc' => 'NL']]);
    }

    public function testToApiModelSendsTheFieldsThatAreSet(): void
    {
        $shipment = (new Shipment())
            ->setReferenceIdentifier('order-1')
            ->setCarrier('POSTNL')
            ->setRecipient(['cc' => 'NL', 'city' => 'Hoofddorp'])
            ->withPackageType('PACKAGE')
            ->setNote('Fragile')
            ->setNote(null);

        $this->assertSame(
            [
                'reference_identifier' => 'order-1',
                'recipient'            => ['cc' => 'NL', 'city' => 'Hoofddorp'],
                'options'              => ['package_type' => 1],
                'carrier'              => 1,
            ],
            json_decode((string) json_encode($shipment), true)
        );
    }

    public function testACloneDoesNotChangeTheOriginal(): void
    {
        $shipment = (new Shipment())->setReferenceIdentifier('order-1')->withPackageType('PACKAGE');

        $clone = clone $shipment;
        $clone->setReferenceIdentifier('order-2')->getOptions()->setPackageType('MAILBOX');

        $this->assertSame('order-1', $shipment->getReferenceIdentifier());
        $this->assertSame(RefShipmentPackageType::Package->value, $shipment->getOptions()->getPackageType());
    }

    public function testShipmentOptionsFromOrderResponseHydratesSharedOptionsContract(): void
    {
        $options = ShipmentOptions::fromOrderResponse([
            'carrier_id'       => 1,
            'shipment_options' => [
                'date'              => '2026-04-17',
                'delivery_type'     => RefTypesDeliveryTypeV2::Standard->value,
                'package_type'      => RefShipmentPackageTypeV2::Package->value,
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
        $this->assertSame(RefTypesDeliveryType::Standard->value, $options->getDeliveryType());
        $this->assertSame(RefShipmentPackageType::Package->value, $options->getPackageType());
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
        $this->assertSame(500, $options->getInsurance()->amount);
    }

    public function testShipmentOptionsToArrayUsesTheApiNames(): void
    {
        $options = (new ShipmentOptions())
            ->setPackageType('PACKAGE')
            ->setSignature(true)
            ->setInsurance(['amount' => 500, 'currency' => 'EUR']);

        $this->assertSame(
            ['insurance' => ['currency' => 'EUR', 'amount' => 500], 'signature' => 1, 'package_type' => 1],
            json_decode((string) json_encode($options->toArray()), true)
        );
    }

    public function testShipmentOptionsSetDeliveryTypeAcceptsSdkEnumAndStoresId(): void
    {
        $options = (new ShipmentOptions())
            ->setDeliveryType(RefTypesDeliveryTypeV2::Morning);

        $this->assertSame(RefTypesDeliveryType::Morning->value, $options->getDeliveryType());
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
