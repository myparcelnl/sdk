<?php

declare(strict_types=1);

namespace Support;

use MyParcelNL\Sdk\src\Support\Collection;
use PHPUnit\Framework\TestCase;

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
}
