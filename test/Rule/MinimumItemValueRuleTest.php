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
    public function provideMinimumItemValueData(): array
    {
        return [
            [
                [
                    'items' => [
                        [
                            'amount' => 50,
                        ],
                        [
                            'amount' => 50,
                        ],
                    ],
                ],
                true,
            ],
            [
                [
                    'items' => [
                        [
                            'amount' => 50,
                        ],
                    ],
                ],
                false,
            ],
            [
                [
                    'items' => [],
                ],
                true,
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
        $cons = new PostNLConsignment();

        foreach ($data['items'] as $item) {
            $cons->addItem(
                (new MyParcelCustomsItem())
                    ->setItemValue($item['amount'])
                    ->setAmount($item['amount'])
                    ->setWeight(1)
                    ->setClassification(123456)
                    ->setCountry('NL')
                    ->setDescription('test')
            );
        }

        $rule->validate($cons);

        self::assertEquals($expected, 0 === count($rule->getErrors()));
    }
}
