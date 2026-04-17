<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Model\Fulfilment\OrderShipmentOptions;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderGetCarrierTest extends TestCase
{
    public function testGetCarrierReturnsCarrierFromShipmentOptions(): void
    {
        $options = (new OrderShipmentOptions())->setCarrierId(CarrierPostNL::ID);

        $order = new Order();
        $order->setDeliveryOptions($options);

        $carrier = $order->getCarrier();

        $this->assertInstanceOf(CarrierPostNL::class, $carrier);
    }

    public function testGetCarrierThrowsWhenNoCarrierSet(): void
    {
        $options = new OrderShipmentOptions();

        $order = new Order();
        $order->setDeliveryOptions($options);

        $this->expectException(\Exception::class);
        $order->getCarrier();
    }
}
