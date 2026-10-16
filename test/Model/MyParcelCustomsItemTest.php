<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model;

use MyParcelNL\Sdk\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class MyParcelCustomsItemTest extends TestCase
{
    public function testSetAndGetDescription(): void
    {
        $item = new MyParcelCustomsItem();
        $item->setDescription('Electronics');

        $this->assertSame('Electronics', $item->getDescription());
    }

    public function testSetAndGetClassification(): void
    {
        $item = new MyParcelCustomsItem();
        $item->setClassification('8541');

        $this->assertSame('8541', $item->getClassification());
    }

    public function testToArray(): void
    {
        $this->assertSame(
            [
                'description'    => 'Shoes',
                'amount'         => 2,
                'weight'         => 500,
                'item_value'     => ['amount' => 1000, 'currency' => 'EUR'],
                'classification' => '6403',
                'country'        => 'NL',
            ],
            $this->createItem()->toArray()
        );
    }

    private function createItem(): MyParcelCustomsItem
    {
        return (new MyParcelCustomsItem())
            ->setDescription('Shoes')
            ->setAmount(2)
            ->setWeight(500)
            ->setItemValue(1000)
            ->setClassification('6403')
            ->setCountry('NL');
    }
}
