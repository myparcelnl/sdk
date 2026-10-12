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

    public function testSetAndGetExternalProductReference(): void
    {
        $item = new MyParcelCustomsItem();
        $item->setExternalProductReference('catalog_item_abc123def456g');

        $this->assertSame('catalog_item_abc123def456g', $item->getExternalProductReference());
    }

    public function testSetExternalProductReferenceAcceptsSixtyFourCharacters(): void
    {
        $reference = str_repeat('a', 64);
        $item      = (new MyParcelCustomsItem())->setExternalProductReference($reference);

        $this->assertSame($reference, $item->getExternalProductReference());
    }

    public function testSetExternalProductReferenceRejectsMoreThanSixtyFourCharacters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new MyParcelCustomsItem())->setExternalProductReference(str_repeat('a', 65));
    }

    public function testToArray(): void
    {
        $this->assertSame(
            [
                'description'                => 'Shoes',
                'amount'                     => 2,
                'weight'                     => 500,
                'item_value'                 => ['amount' => 1000, 'currency' => 'EUR'],
                'classification'             => '6403',
                'country'                    => 'NL',
                'external_product_reference' => null,
            ],
            $this->createItem()->toArray()
        );
    }

    public function testToArrayWithoutNullLeavesOutAnUnsetExternalProductReference(): void
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
            $this->createItem()->toArrayWithoutNull()
        );
    }

    public function testToArrayContainsTheExternalProductReferenceWhenSet(): void
    {
        $item = $this->createItem()->setExternalProductReference('catalog_item_abc123def456g');

        $this->assertSame('catalog_item_abc123def456g', $item->toArray()['external_product_reference']);
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
