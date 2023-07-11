<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderNotesCollection;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderNotesCollectionTest extends TestCase
{
    public function testSave() {
        $apiKey               = $this->getApiKey();
        $orderNotesCollection = (new OrderNotesCollection())->setApiKey($apiKey);

        // get last order to have a valid uuid
        $collection = OrderCollection::query($this->getApiKey());

        if ($collection->isEmpty()) {
            $this->markTestSkipped('No orders found');
        }

        $uuid = $collection->first()->getUuid();

        $orderNotesCollection->push(
            (new OrderNote())
                ->setUuid($uuid)
                ->setAuthor('webshop')
                ->setNote('ordernote test save')
        );

        $this->assertSame([], $orderNotesCollection->save());
    }
}
