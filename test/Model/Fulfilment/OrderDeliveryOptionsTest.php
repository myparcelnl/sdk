<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class OrderDeliveryOptionsTest extends TestCase
{
    public function testReadsTheShipmentOptionsOfAnOrderResponse(): void
    {
        $options = (new Order(['shipment' => ['shipment_options' => [
            'date'           => '2026-11-10',
            'delivery_type'  => 2,
            'package_type'   => 1,
            'signature'      => true,
            'only_recipient' => 0,
            'insurance'      => 2.5,
        ]]]))->getDeliveryOptions();

        self::assertSame('2026-11-10', $options->deliveryDate);
        self::assertSame(2, $options->deliveryType);
        self::assertSame(1, $options->packageType->value);
        self::assertEquals(1, $options->signature);
        self::assertEquals(0, $options->onlyRecipient);
        self::assertSame(250, $options->insurance->amount);
    }

    /**
     * The Order API can send the v2 name of a package type and a delivery type.
     */
    public function testReadsAV2NameAsTheId(): void
    {
        $options = (new Order(['shipment' => ['shipment_options' => ['package_type' => 'PACKAGE', 'delivery_type' => 'STANDARD_DELIVERY']]]))->getDeliveryOptions();

        self::assertSame(1, $options->packageType->value);
        self::assertSame(2, $options->deliveryType);
    }

    public function testReadsANumericStringAsTheId(): void
    {
        $options = (new Order(['shipment' => ['shipment_options' => ['package_type' => '2', 'delivery_type' => '4']]]))->getDeliveryOptions();

        self::assertSame(2, $options->packageType->value);
        self::assertSame(4, $options->deliveryType);
    }

    public function testThrowsForATypeNameThatTheMapperDoesNotKnow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"NOPE"');

        new Order(['shipment' => ['shipment_options' => ['package_type' => 'NOPE']]]);
    }

    public function testReadsTheOptionsKeyToo(): void
    {
        $options = (new Order(['shipment' => ['options' => ['delivery_date' => '2026-11-11', 'package_type' => 2]]]))->getDeliveryOptions();

        self::assertSame('2026-11-11', $options->deliveryDate);
        self::assertSame(2, $options->packageType->value);
    }
}
