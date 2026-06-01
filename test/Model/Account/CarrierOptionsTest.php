<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Account;

use MyParcelNL\Sdk\Model\Account\CarrierOptions;
use MyParcelNL\Sdk\Model\Carrier\CarrierBRT;
use MyParcelNL\Sdk\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\Model\Carrier\CarrierInPost;
use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Carrier\CarrierPosteItaliane;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class CarrierOptionsTest extends TestCase
{
    public function testCarrierOptions(): void
    {
        foreach ($this->provideTestData() as $testData) {
            $carrierOptions = new CarrierOptions($testData['options']);
            $expected = $testData['expected'];

            self::assertEquals($expected['enabled'], $carrierOptions->isEnabled());
            self::assertEquals($expected['optional'], $carrierOptions->isOptional());
            self::assertEquals($expected['type'], $carrierOptions->getType());
            self::assertEquals($expected['label'], $carrierOptions->getLabel());
            self::assertInstanceOf($expected['carrier'], $carrierOptions->getCarrier());
        }
    }

    /**
     * @return \Generator
     */
    private function provideTestData()
    {
        $option_collections = [
            ['options' => [
                'enabled' => true,
                'optional' => true,
                'carrier' => [
                    'id' => 1,
                ],
                'label' => NULL,
                'type' => 'main',
            ],
                'expected' => [
                    'enabled' => true,
                    'optional' => true,
                    'carrier' => CarrierPostNL::class,
                    'label' => CarrierPostNL::HUMAN,
                    'type' => 'main',

                ]
            ],
            ['options' => [
                'enabled' => false,
                'optional' => false,
                'carrier' => [
                    'id' => 4,
                ],
                'label' => 'custom',
                'type' => 'custom',
            ],
                'expected' => [
                    'enabled' => false,
                    'optional' => false,
                    'carrier' => CarrierDPD::class,
                    'label' => 'custom',
                    'type' => 'custom',

                ]
            ],
            ['options' => [
                'enabled' => true,
                'optional' => false,
                'carrier' => [
                    'id' => 15,
                ],
            ],
                'expected' => [
                    'enabled' => true,
                    'optional' => false,
                    'carrier' => CarrierBRT::class,
                    'label' => CarrierBRT::HUMAN,
                    'type' => CarrierBRT::HUMAN,
                ],
            ],
            ['options' => [
                'enabled' => true,
                'optional' => false,
                'carrier' => [
                    'id' => 17,
                ],
            ],
                'expected' => [
                    'enabled' => true,
                    'optional' => false,
                    'carrier' => CarrierInPost::class,
                    'label' => CarrierInPost::HUMAN,
                    'type' => CarrierInPost::HUMAN,
                ],
            ],
            ['options' => [
                'enabled' => true,
                'optional' => false,
                'carrier' => [
                    'id' => 18,
                ],
            ],
                'expected' => [
                    'enabled' => true,
                    'optional' => false,
                    'carrier' => CarrierPosteItaliane::class,
                    'label' => CarrierPosteItaliane::HUMAN,
                    'type' => CarrierPosteItaliane::HUMAN,
                ],
            ],
        ];

        foreach ($option_collections as $options) {
            yield $options;
        }
    }
}
