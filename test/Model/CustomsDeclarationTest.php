<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model;

use MyParcelNL\Sdk\Model\CustomsDeclaration;
use MyParcelNL\Sdk\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/** Covers the Order v1 serialization of a customs declaration. */
class CustomsDeclarationTest extends TestCase
{
    public function testToArray(): void
    {
        $item = (new MyParcelCustomsItem())
            ->setDescription('Shoes')
            ->setAmount(2)
            ->setWeight(500)
            ->setItemValue(1000)
            ->setClassification('6403')
            ->setCountry('US');

        $declaration = (new CustomsDeclaration())
            ->setContents(1)
            ->setInvoice('1234');
        $declaration->setWeight(500);
        $declaration->addCustomsItem($item);

        $this->assertSame(
            [
                'contents' => 1,
                'items'    => [$item->toArray()],
                'invoice'  => '1234',
                'weight'   => 500,
            ],
            $declaration->toArray()
        );
    }

    public function testToArrayWithoutValues(): void
    {
        $this->assertSame(
            [
                'contents' => null,
                'items'    => [],
                'invoice'  => null,
                'weight'   => null,
            ],
            (new CustomsDeclaration())->toArray()
        );
    }
}
