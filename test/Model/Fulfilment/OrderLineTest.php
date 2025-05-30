<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\Model\Fulfilment\OrderLine;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderLineTest extends TestCase
{
    public function testGetPrice(): void
    {
        $orderLine = (new OrderLine())->setPrice(100);
        self::assertEquals(100, $orderLine->getPrice());
    }

    public function testGetProduct(): void
    {
        $orderLine = new OrderLine();
        $product   = $orderLine->getProduct()
            ->setName('Product');
        self::assertEquals(
            'Product',
            $product->getName()
        );
    }

    public function testGetQuantity(): void
    {
        $orderLine = (new OrderLine())->setQuantity(2);
        self::assertEquals(2, $orderLine->getQuantity());
    }
}
