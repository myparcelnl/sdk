<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Factory\Account;

use MyParcelNL\Sdk\src\Factory\Account\CarrierConfigurationFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

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
                        'default_drop_off_point' => '217171',
                        'monday_cutoff_time'     => '09:30',
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
        $carrierConfiguration = CarrierConfigurationFactory::create($testData, true, $this->getApiKey());

        self::assertInstanceOf(DropOffPoint::class, $carrierConfiguration->getDefaultDropOffPoint());
    }
}
