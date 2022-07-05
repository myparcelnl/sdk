<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\src\Helper\Utils;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class UtilsTest extends TestCase
{
    /**
     * @return array
     */
    public function provideEmptyValuesData(): array
    {
        return [
            [
                [
                    'person'    => null,
                    'email'     => 'support@myparcel.nl',
                    'reference' => ''
                ],
                [
                    'email', 'person', 'reference'
                ],
                [
                    'person', 'reference'
                ]
            ],
            [
                [
                    'person'    => null,
                    'email'     => 'support@myparcel.nl',
                    'reference' => ''
                ],
                [
                    'email', 'person'
                ],
                [
                    'person'
                ]
            ]
        ];
    }

    /**
     * @dataProvider provideEmptyValuesData
     * @param $array
     * @param $requiredFields
     * @param $expected
     *
     * @return void
     */
    public function testEmptyValues($array, $requiredFields, $expected): void
    {
        $emptyValues = Utils::emptyValues($array, $requiredFields);
        self::assertIsArray($emptyValues);
        self::assertEquals($expected, $emptyValues);
    }
}
