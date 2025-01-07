<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Support;

use MyParcelNL\Sdk\src\Support\Collection;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideToArrayWithoutNullData(): array
    {
        return [
            'plain array'        => [
                ['a' => null, 'b' => '1'],
                ['b' => '1'],
            ],
            'nested array'       => [
                ['a' => null, 'b' => '1', 'c' => ['a' => null, 'd' => '1']],
                ['b' => '1', 'c' => ['d' => '1']],
            ],
            'collections'        => [
                [
                    new Collection(['a' => null, 'b' => '1']),
                    new Collection(['a' => new Collection(), 'b' => '1']),
                ],
                [
                    ['b' => '1',],
                    ['a' => [], 'b' => '1'],
                ],
            ],
            'nested collections' => [
                [
                    new Collection(['a' => null, 'b' => 1, 'c' => new Collection(['a' => null, 'd' => '1'])]),
                    new Collection(['a' => '44', 'b' => '1']),
                ],
                [
                    ['b' => 1, 'c' => ['d' => '1']],
                    ['a' => '44', 'b' => '1'],
                ],
            ],
        ];
    }

    /**
     * @param  array $input
     * @param  array $output
     *
     * @dataProvider provideToArrayWithoutNullData
     */
    public function testToArrayWithoutNull(array $input, array $output): void
    {
        self::assertSame(((new Collection($input))->toArrayWithoutNull()), $output);
    }

    public function testGroupBy(): void
    {
        $collection = new Collection([
             ['a' => 1, 'b' => 'appel', 'c' => 'broccoli'],
             ['a' => 3, 'b' => 'peer', 'c' => 'wortel'],
             ['a' => 2, 'b' => 'banaan', 'c' => 'bloemkool'],
             ['a' => 3, 'b' => 'peer', 'c' => 'spinazie'],
             ['a' => 3, 'b' => 'peer', 'c' => 'bloemkool'],
             ['a' => 1, 'b' => 'peer', 'c' => 'wortel'],
             ['a' => 2, 'b' => 'appel', 'c' => 'spinazie'],
             ['a' => 2, 'b' => 'banaan', 'c' => 'broccoli'],
             ['a' => 3, 'b' => 'peer', 'c' => 'broccoli'],
             ['a' => 3, 'b' => 'appel', 'c' => 'bloemkool'],
        ]);

        $grouped = $collection->groupBy(function ($item) {
            return $item['a'];
        });

        self::assertSame(
            [
                1 => [
                    ['a' => 1, 'b' => 'appel', 'c' => 'broccoli'],
                    ['a' => 1, 'b' => 'peer', 'c' => 'wortel'],
                ],
                3 => [
                    ['a' => 3, 'b' => 'peer', 'c' => 'wortel'],
                    ['a' => 3, 'b' => 'peer', 'c' => 'spinazie'],
                    ['a' => 3, 'b' => 'peer', 'c' => 'bloemkool'],
                    ['a' => 3, 'b' => 'peer', 'c' => 'broccoli'],
                    ['a' => 3, 'b' => 'appel', 'c' => 'bloemkool'],
                ],
                2 => [
                    ['a' => 2, 'b' => 'banaan', 'c' => 'bloemkool'],
                    ['a' => 2, 'b' => 'appel', 'c' => 'spinazie'],
                    ['a' => 2, 'b' => 'banaan', 'c' => 'broccoli'],
                ],
            ],
            $grouped->toArray()
        );
    }

    public function testGroupInto(): void
    {
        $collection = new Collection([
             ['a' => 1, 'b' => 'appel', 'c' => 'broccoli'],
             ['a' => 3, 'b' => 'peer', 'c' => 'wortel'],
             ['a' => 2, 'b' => 'banaan', 'c' => 'bloemkool'],
             ['a' => 3, 'b' => 'peer', 'c' => 'spinazie'],
             ['a' => 3, 'b' => 'peer', 'c' => 'bloemkool'],
             ['a' => 1, 'b' => 'peer', 'c' => 'wortel'],
             ['a' => 2, 'b' => 'appel', 'c' => 'spinazie'],
             ['a' => 2, 'b' => 'banaan', 'c' => 'broccoli'],
             ['a' => 3, 'b' => 'peer', 'c' => 'broccoli'],
             ['a' => 3, 'b' => 'appel', 'c' => 'bloemkool'],
        ]);

        $grouped = $collection->groupInto(['a', 'b']);

        self::assertSame(
            array (
                'afb29a44c64418a0507392632ee9b911' =>
                    array (
                        0 =>
                            array (
                                'a' => 1,
                                'b' => 'appel',
                                'c' => 'broccoli',
                            ),
                    ),
                '830ad1465ba6193bdc926b5aa4f4663a' =>
                    array (
                        0 =>
                            array (
                                'a' => 3,
                                'b' => 'peer',
                                'c' => 'wortel',
                            ),
                        1 =>
                            array (
                                'a' => 3,
                                'b' => 'peer',
                                'c' => 'spinazie',
                            ),
                        2 =>
                            array (
                                'a' => 3,
                                'b' => 'peer',
                                'c' => 'bloemkool',
                            ),
                        3 =>
                            array (
                                'a' => 3,
                                'b' => 'peer',
                                'c' => 'broccoli',
                            ),
                    ),
                'fc2554ed6a4a01fe6decc2687f976d1a' =>
                    array (
                        0 =>
                            array (
                                'a' => 2,
                                'b' => 'banaan',
                                'c' => 'bloemkool',
                            ),
                        1 =>
                            array (
                                'a' => 2,
                                'b' => 'banaan',
                                'c' => 'broccoli',
                            ),
                    ),
                'c301ad1cc996740d20ce50b40ce5701e' =>
                    array (
                        0 =>
                            array (
                                'a' => 1,
                                'b' => 'peer',
                                'c' => 'wortel',
                            ),
                    ),
                '63f9062403bd4ea9414cbfaf5a957c36' =>
                    array (
                        0 =>
                            array (
                                'a' => 2,
                                'b' => 'appel',
                                'c' => 'spinazie',
                            ),
                    ),
                '3f68cd66281efed34673d0c7c4a38c86' =>
                    array (
                        0 =>
                            array (
                                'a' => 3,
                                'b' => 'appel',
                                'c' => 'bloemkool',
                            ),
                    ),
            ),
            $grouped->toArray()
        );
    }
}
