<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderNotesCollection;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderNotesCollectionTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testSave() {
        $apiKey               = $this->getApiKey();
        $orderNotesCollection = (new OrderNotesCollection())->setApiKey($apiKey);

        $collection = OrderCollection::query($this->getApiKey());

        if ($collection->isEmpty()) {
            $this->markTestSkipped('No orders found');
        }

        $uuid = $collection->first()->getUuid();

        array_map(static function(string $note) use ($orderNotesCollection, $uuid) {
            $orderNotesCollection->push(
                (new OrderNote())
                    ->setOrderUuid($uuid)
                    ->setAuthor('webshop')
                    ->setNote($note)
            );
            }, ['first ordernote test save', 'second ordernote test save']);

        $savedNotes = $orderNotesCollection->save();

        $this->assertSame($orderNotesCollection->toArray(), $savedNotes->toArray());
    }
}
