<?php

declare(strict_types=1);

namespace Factory\Account;

use MyParcelNL\Sdk\src\Factory\Account\CarrierConfigurationFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use PHPUnit\Framework\TestCase;

class CarrierConfigurationFactoryTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideCreateCarrierConfigurationData(): array
    {
        return [
            'With identifier only'        => [
                [
                    'carrier_id'                        => 1,
                    'default_drop_off_point'            => null,
                    'default_drop_off_point_identifier' => '217171',
                ],
            ],
            'With existing dropoff point' => [
                [
                    'carrier_id'                        => 5,
                    'default_drop_off_point'            => [
                        'box_number'        => null,
                        'cc'                => 'NL',
                        'city'              => 'Arnhem',
                        'location_code'     => '73658f70-417a-48d2-82bb-291f3dccce93',
                        'location_name'     => 'Red je Pakketje',
                        'number'            => '24',
                        'number_suffix'     => 'K',
                        'postal_code'       => '6827DE',
                        'region'            => null,
                        'retail_network_id' => null,
                        'state'             => null,
                        'street'            => 'Hondsstraat',
                    ],
                    'default_drop_off_point_identifier' => null,
                ],
            ],
            'From carrier'                => [
                [
                    'carrier'                           => new CarrierPostNL(),
                    'default_drop_off_point'            => null,
                    'default_drop_off_point_identifier' => '217171',
                ],
            ],
            'From API'                    => [
                [
                    'carrier_id'    => 5,
                    'configuration' => [
                        'default_cutoff_time'    => '09:30',
                        'default_drop_off_point' => 'e9149b66-7bee-439b-bab0-7a5d92ddc519',
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
        $carrierConfiguration = CarrierConfigurationFactory::create($testData, true, getenv('API_KEY'));

        self::assertTrue(is_a($carrierConfiguration->getDefaultDropOffPoint(), DropOffPoint::class));
    }
}
