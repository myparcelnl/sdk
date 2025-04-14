<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\Collection\Fulfilment\OrderNotesCollection;
use MyParcelNL\Sdk\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderNotesCollectionTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     */
    public function testSave() {
        $apiKey               = $this->getApiKey();
        $orderNotesCollection = (new OrderNotesCollection())->setApiKey($apiKey);

        $collection = OrderCollection::query($this->getApiKey());

        if ($collection->isEmpty()) {
            $this->markTestSkipped('No orders found');
        }

        $i = 1;
        foreach ($collection->getIterator() as $order) {
            $uuid = $order->getUuid();

            array_map(static function(string $note) use ($orderNotesCollection, $uuid) {
                $orderNotesCollection->push(
                    (new OrderNote())
                        ->setOrderUuid($uuid)
                        ->setAuthor('webshop')
                        ->setNote($note)
                );
            }, ["first ordernote test save $i", "second ordernote test save $i"]);

            if (2 === $i) {
                break;
            }

            ++$i;
        }

        $savedNotes = $orderNotesCollection->save();

        $this->assertSame($orderNotesCollection->toArray(), $savedNotes->toArray());
    }
}
