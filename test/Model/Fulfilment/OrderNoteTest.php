<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderNoteTest extends TestCase
{
    public function testToApiClass(): void
    {
        $orderNote = (new OrderNote())
            ->setOrderUuid('uuid')
            ->setAuthor('customer')
            ->setNote('Test');

        $this->assertEquals('uuid', $orderNote->getOrderUuid());
        $this->assertEquals('customer', $orderNote->getAuthor());
        $this->assertEquals('Test', $orderNote->getNote());

        $this->assertEquals((object)[
            'author' => 'customer',
            'note'   => 'Test',
        ], $orderNote->toApiObject());
    }

    /**
     * @param  array $data
     *
     * @throws \Exception
     * @dataProvider notesProvider
     */
    public function testValidate(array $data): void
    {
        $this->expectExceptionMessage($data['expectedError']);
        $note = new OrderNote($data['orderNote']);
        $note->validate();
    }

    public static function notesProvider(): array
    {
        $stringTooLong = str_repeat('!', 2501);
        return [
            'not all values' =>
                [
                    'data' => [
                        'orderNote'     => [
                            'orderUuid' => 'uuid',
                            'author'    => 'customer',
                        ],
                        'expectedError' => 'All properties must be set on MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote',
                    ],
                ],
            'note too long'  => [
                'data' => [
                    'orderNote'     => [
                        'orderUuid' => 'uuid',
                        'author'    => 'customer',
                        'note'      => $stringTooLong,
                    ],
                    'expectedError' => 'The note may not be longer than 2500 characters',
                ],
            ],
            'illegal author' => [
                'data' => [
                    'orderNote'     => [
                        'orderUuid' => 'uuid',
                        'author'    => 'wrong',
                        'note'      => 'Test',
                    ],
                    'expectedError' => 'Author must be one of customer, webshop',
                ],
            ]
        ];
    }
}
