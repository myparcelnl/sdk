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
        $apiKey = $this->getApiKey();
        $orderNotesCollection = (new OrderNotesCollection())->setApiKey($apiKey);

        // Mock response for OrderCollection::query
        $orderQueryResponse = [
            'response' => json_encode([
                'data' => [
                    'orders' => [
                        [
                            'uuid' => 'order-uuid-1',
                            'shop_id' => 12345,
                            'status' => 'pending'
                        ],
                        [
                            'uuid' => 'order-uuid-2',
                            'shop_id' => 12345,
                            'status' => 'processing'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        // Mock response for orderNotesCollection->save()
        $saveNotesResponse = [
            'response' => json_encode([
                'data' => [
                    'order_notes' => [
                        [
                            'uuid' => 'note-uuid-1',
                            'order_uuid' => 'order-uuid-1',
                            'author' => 'webshop',
                            'note' => 'first ordernote test save 1'
                        ],
                        [
                            'uuid' => 'note-uuid-2', 
                            'order_uuid' => 'order-uuid-1',
                            'author' => 'webshop',
                            'note' => 'second ordernote test save 1'
                        ],
                        [
                            'uuid' => 'note-uuid-3',
                            'order_uuid' => 'order-uuid-2',
                            'author' => 'webshop',
                            'note' => 'first ordernote test save 2'
                        ],
                        [
                            'uuid' => 'note-uuid-4',
                            'order_uuid' => 'order-uuid-2',
                            'author' => 'webshop', 
                            'note' => 'second ordernote test save 2'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 201
        ];


        $mockCurl = $this->mockCurl();
        
        $mockCurl->shouldReceive('write')->times(3)->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->times(3)->andReturn($orderQueryResponse, $orderQueryResponse, $saveNotesResponse);
        $mockCurl->shouldReceive('close')->times(3)->andReturnSelf();

        $collection = OrderCollection::query($apiKey);

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

        self::assertInstanceOf(OrderNotesCollection::class, $savedNotes);
        self::assertGreaterThan(0, $savedNotes->count());
        
        $firstNote = $savedNotes->first();
        self::assertInstanceOf(OrderNote::class, $firstNote);
        self::assertNotEmpty($firstNote->getOrderUuid());
        self::assertNotEmpty($firstNote->getAuthor());
    }
}
