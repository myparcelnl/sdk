<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderGetCarrierTest extends TestCase
{
    public function testGetCarrierReturnsCarrierFromOrderShipment(): void
    {
        $order = new Order();
        $order->setCarrierId(CarrierPostNL::ID);

        $carrier = $order->getCarrier();

        $this->assertInstanceOf(CarrierPostNL::class, $carrier);
    }

    public function testGetCarrierThrowsWhenNoCarrierSet(): void
    {
        $order = new Order();

        $this->expectException(\Exception::class);
        $order->getCarrier();
    }
}
