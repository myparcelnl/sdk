<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderNotesCollection;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderNotesCollectionTest extends TestCase
{
    const BUNCH_OF_NOTES = [

    ];
    public function testSave() {
        $apiKey               = $this->getApiKey();
        $orderNotesCollection = (new OrderNotesCollection())->setApiKey($apiKey);

        $orderNotesCollection->push(
            (new OrderNote())
                ->setUuid('uuid')
                ->setAuthor('customer')
                ->setNote('Test')
        );

        $this->assertSame([], $orderNotesCollection->save());
    }
}
