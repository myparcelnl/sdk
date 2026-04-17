<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Model\Fulfilment\OrderShipmentOptions;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderShipmentOptionsTest extends TestCase
{
    public function testFromOrderResponseHydratesAllFields(): void
    {
        $options = OrderShipmentOptions::fromOrderResponse([
            'carrier_id'       => 1,
            'shipment_options' => [
                'date'              => '2026-04-17',
                'delivery_type'     => 'STANDARD_DELIVERY',
                'package_type'      => 'PACKAGE',
                'signature'         => true,
                'collect'           => false,
                'receipt_code'      => true,
                'only_recipient'    => true,
                'age_check'         => false,
                'large_format'      => true,
                'return'            => false,
                'priority_delivery' => true,
                'label_description' => 'Test label',
                'insurance'         => 500,
            ],
        ]);

        $this->assertSame(1, $options->getCarrierId());
        $this->assertSame('2026-04-17', $options->getDate());
        $this->assertSame('STANDARD_DELIVERY', $options->getDeliveryType());
        $this->assertSame('PACKAGE', $options->getPackageType());
        $this->assertTrue($options->hasSignature());
        $this->assertFalse($options->hasCollect());
        $this->assertTrue($options->hasReceiptCode());
        $this->assertTrue($options->hasOnlyRecipient());
        $this->assertFalse($options->hasAgeCheck());
        $this->assertTrue($options->hasLargeFormat());
        $this->assertFalse($options->isReturn());
        $this->assertTrue($options->isPriorityDelivery());
        $this->assertSame('Test label', $options->getLabelDescription());
        $this->assertSame(500, $options->getInsurance());
    }

    public function testFromOrderResponseHandlesEmptyOptions(): void
    {
        $options = OrderShipmentOptions::fromOrderResponse([]);

        $this->assertNull($options->getCarrierId());
        $this->assertNull($options->getDate());
        $this->assertNull($options->getDeliveryType());
        $this->assertNull($options->getPackageType());
        $this->assertNull($options->hasSignature());
        $this->assertNull($options->getInsurance());
    }

    public function testSettersReturnSelf(): void
    {
        $options = new OrderShipmentOptions();

        $result = $options
            ->setCarrierId(1)
            ->setDate('2026-04-17')
            ->setDeliveryType(RefTypesDeliveryTypeV2::STANDARD)
            ->setPackageType(RefShipmentPackageTypeV2::PACKAGE)
            ->setSignature(true)
            ->setCollect(false)
            ->setReceiptCode(true)
            ->setOnlyRecipient(false)
            ->setAgeCheck(true)
            ->setLargeFormat(false)
            ->setReturn(true)
            ->setPriorityDelivery(false)
            ->setLabelDescription('desc')
            ->setInsurance(250);

        $this->assertSame($options, $result);
        $this->assertSame(1, $options->getCarrierId());
        $this->assertTrue($options->hasSignature());
        $this->assertTrue($options->hasReceiptCode());
        $this->assertTrue($options->hasAgeCheck());
        $this->assertTrue($options->isReturn());
        $this->assertSame('desc', $options->getLabelDescription());
        $this->assertSame(250, $options->getInsurance());
    }

    public function testGetDeliveryTypeIdFromV2Enum(): void
    {
        $options = (new OrderShipmentOptions())->setDeliveryType(RefTypesDeliveryTypeV2::MORNING);

        $this->assertSame(RefTypesDeliveryType::MORNING, $options->getDeliveryTypeId());
    }

    public function testGetDeliveryTypeIdFromNumericString(): void
    {
        $options = (new OrderShipmentOptions())->setDeliveryType('2');

        $this->assertSame(2, $options->getDeliveryTypeId());
    }

    public function testGetDeliveryTypeIdReturnsNullForUnknown(): void
    {
        $options = (new OrderShipmentOptions())->setDeliveryType('NONEXISTENT');

        $this->assertNull($options->getDeliveryTypeId());
    }

    public function testGetDeliveryTypeIdReturnsNullWhenNotSet(): void
    {
        $this->assertNull((new OrderShipmentOptions())->getDeliveryTypeId());
    }

    public function testGetPackageTypeIdFromV2Enum(): void
    {
        $options = (new OrderShipmentOptions())->setPackageType(RefShipmentPackageTypeV2::PACKAGE);

        $this->assertSame(RefShipmentPackageType::PACKAGE, $options->getPackageTypeId());
    }

    public function testGetPackageTypeIdFromNumericString(): void
    {
        $options = (new OrderShipmentOptions())->setPackageType('1');

        $this->assertSame(1, $options->getPackageTypeId());
    }

    public function testGetPackageTypeIdReturnsNullForUnknown(): void
    {
        $options = (new OrderShipmentOptions())->setPackageType('NONEXISTENT');

        $this->assertNull($options->getPackageTypeId());
    }

    public function testGetPackageTypeIdReturnsNullWhenNotSet(): void
    {
        $this->assertNull((new OrderShipmentOptions())->getPackageTypeId());
    }

    public function testToArrayReturnsExpectedStructure(): void
    {
        $options = (new OrderShipmentOptions())
            ->setCarrierId(1)
            ->setDate('2026-04-17')
            ->setDeliveryType(RefTypesDeliveryTypeV2::STANDARD)
            ->setPackageType(RefShipmentPackageTypeV2::PACKAGE)
            ->setSignature(true)
            ->setInsurance(500);

        $array = $options->toArray();

        $this->assertSame(1, $array['carrier']);
        $this->assertSame('2026-04-17', $array['date']);
        $this->assertTrue($array['shipmentOptions']['signature']);
        $this->assertSame(500, $array['shipmentOptions']['insurance']);
    }
}
