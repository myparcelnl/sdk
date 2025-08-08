<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Factory\Account;

use MyParcelNL\Sdk\Factory\Account\CarrierConfigurationFactory;
use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;
use MyParcelNL\Sdk\Test\Mock\MockMyParcelCurl;

class CarrierConfigurationFactoryTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideCreateCarrierConfigurationData(): array
    {
        return [
//            'With identifier only'        => [
//                [
//                    'carrier_id'                        => 1,
//                    'default_drop_off_point'            => null,
//                    'default_drop_off_point_identifier' => '217171',
//                ],
//            ],
            'With existing dropoff point' => [
                [
                    'carrier_id'                        => 1,
                    'default_drop_off_point'            => [
                        'box_number'        => null,
                        'cc'                => 'NL',
                        'city'              => 'Hoofddorp',
                        'location_code'     => '217171',
                        'location_name'     => 'PostNL',
                        'number'            => '124',
                        'number_suffix'     => null,
                        'postal_code'       => '2132DM',
                        'region'            => null,
                        'retail_network_id' => null,
                        'state'             => null,
                        'street'            => 'Marktlaan',
                    ],
                    'default_drop_off_point_identifier' => null,
                ],
            ],
//            'From carrier'                => [
//                [
//                    'carrier'                           => new CarrierPostNL(),
//                    'default_drop_off_point'            => null,
//                    'default_drop_off_point_identifier' => '217171',
//                ],
//            ],
            'From API'                    => [
                [
                    'carrier_id'    => 1,
                    'configuration' => [
                        'default_cutoff_time'    => '09:30',
                        'default_drop_off_point' => '171963',
                        'monday_cutoff_time'     => '09:30'
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideCreateCarrierConfigurationData
     */
    public function testCreateCarrierConfiguration(array $testData): void
    {

        MockMyParcelCurl::addResponse([
            'response' => json_encode([
            'data' => [
                'drop_off_points' => [
                    [
                        'cc' => 'NL',
                        'city' => 'Amsterdam',
                        'location_code' => '171963',
                        'location_name' => 'Test Drop Off Point',
                        'number' => '123',
                        'number_suffix' => null,
                        'postal_code' => '1000AA',
                        'region' => null,
                        'retail_network_id' => null,
                        'state' => null,
                        'street' => 'Teststraat'
                    ]
                ]
            ]
            ])
        ]);

        $carrierConfiguration = CarrierConfigurationFactory::create($testData, true, $this->getApiKey());


        self::assertInstanceOf(DropOffPoint::class, $carrierConfiguration->getDefaultDropOffPoint());
    }
}
