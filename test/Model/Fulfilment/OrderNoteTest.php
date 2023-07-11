<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderNoteTest extends TestCase
{
    public function testToArray(): void
    {
        $orderNote = (new OrderNote())
            ->setUuid('uuid')
            ->setAuthor('customer')
            ->setNote('Test');

        $this->assertEquals('uuid', $orderNote->getUuid());
        $this->assertEquals('customer', $orderNote->getAuthor());
        $this->assertEquals('Test', $orderNote->getNote());

        $this->assertEquals([
            'author' => 'customer',
            'note'   => 'Test',
        ], $orderNote->toArray());
    }

    /**
     * @param  array $data
     *
     * @throws \Exception
     * @dataProvider bunchOfNotes
     */
    public function testValidator(array $data) {
        $this->expectExceptionMessage($data['expectedError']);
        $note = new OrderNote($data['orderNote']);
        $note->validate();
    }

    public function bunchOfNotes(): array
    {
        $stringTooLong = str_repeat('!', 2501);
        return [
            'not all values' =>
                [
                    'data' => [
                        'orderNote'     => [
                            'author' => 'customer',
                            'note'   => 'Test',
                        ],
                        'expectedError' => 'All properties must be set on MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote',
                    ],
                ],
            'note too long'  => [
                'data' => [
                    'orderNote'     => [
                        'uuid'   => 'uuid',
                        'author' => 'customer',
                        'note'   => $stringTooLong,
                    ],
                    'expectedError' => 'The note may not be longer than 2500 characters',
                ],
            ],
            'illegal author' => [
                'data' => [
                    'orderNote'     => [
                        'uuid'   => 'uuid',
                        'author' => 'wrong',
                        'note'   => 'Test',
                    ],
                    'expectedError' => 'Author must be one of customer, webshop',
                ],
            ]
        ];
    }
}
