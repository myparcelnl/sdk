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
}
