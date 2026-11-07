<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

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

    public function testReadsTheOptionsKeyToo(): void
    {
        $options = (new Order(['shipment' => ['options' => ['delivery_date' => '2026-11-11', 'package_type' => 2]]]))->getDeliveryOptions();

        self::assertSame('2026-11-11', $options->deliveryDate);
        self::assertSame(2, $options->packageType->value);
    }
}
