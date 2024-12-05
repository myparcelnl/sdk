<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Rule;

use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Rule\Consignment\MinimumItemValueRule;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class MinimumItemValueRuleTest extends TestCase
{
    /**
     * @return array
     */
    public static function provideMinimumItemValueData(): array
    {
        return [
            [
                [
                    'items' => [
                        [
                            'value' => 50,
                        ],
                        [
                            'value' => 50,
                        ],
                    ],
                ],
                'expected'=>true,
            ],
            [
                [
                    'items' => [
                        [
                            'value' => 5000,
                        ],
                        [
                            'value' => 9995,
                        ],
                    ],
                ],
                'expected'=>true,
            ],
            [
                [
                    'items' => [
                        [
                            'value' => 50,
                        ],
                    ],
                ],
                'expected'=>false,
            ],
            [
                [
                    'items' => [],
                ],
                'expected'=>true,
            ],
        ];
    }

    /**
     * @param array $data
     * @param bool $expected
     *
     * @dataProvider provideMinimumItemValueData
     */
    public function testMinimumItemValueRule(array $data, bool $expected): void
    {
        $rule = new MinimumItemValueRule();
        $consignment = new PostNLConsignment();

        foreach ($data['items'] as $item) {
            $consignment->addItem(
                (new MyParcelCustomsItem())
                    ->setItemValue($item['value'])
                    ->setAmount(1)
                    ->setWeight(1)
                    ->setClassification(123456)
                    ->setCountry('NL')
                    ->setDescription('test')
            );
        }

        $rule->validate($consignment);

        self::assertEquals($expected, 0 === count($rule->getErrors()));
    }
}
