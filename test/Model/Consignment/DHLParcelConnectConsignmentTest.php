<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierDHLParcelConnect;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class DHLParcelConnectConsignmentTest extends ConsignmentTestCase
{
    public const  PICKUP_LOCATION_CODE = 'pickup_location_code';
    private const LOCATION_CODE        = 'location_code';

    /**
     * @return array
     * @throws \Exception
     */
    public function provideDHLParcelConnectConsignmentsData(): array
    {
        $pickupInformation = [
            self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_PICKUP,
            self::PICKUP_STREET => 'Rte des fusilles de la resis',
            self::PICKUP_CITY   => 'Nanterre',
            self::PICKUP_NUMBER => '110',
            self::PICKUP_POSTAL_CODE => '92000',
            self::PICKUP_COUNTRY => 'FR',
            self::PICKUP_LOCATION_NAME => 'MARCHE D A COTE',
            self::PICKUP_LOCATION_CODE => 'S2203',
            self::RETAIL_NETWORK_ID => '',
        ];

        return $this->createConsignmentProviderDataset([
            'Signature' => [
                    self::SIGNATURE => true,
                ] + $pickupInformation,
            'Insurance' => [
                    self::expected(self::INSURANCE) => 0,
                    self::SIGNATURE                 => true,
                ] + $pickupInformation,
            'Return'    => [
                    self::RETURN                 => true,
                    self::expected(self::RETURN) => false,
                    self::SIGNATURE              => true,
                ] + $pickupInformation,
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideDHLParcelConnectConsignmentsData
     */
    public function testDHLParcelConnectConsignments(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getDHLParcelConnectFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'signature' => $testData[self::SIGNATURE] ?? false,
            'insurance' => $testData[self::INSURANCE] ?? 0,
            'return' => $testData[self::RETURN] ?? false,
            'delivery_type' => $testData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_PICKUP,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'FR',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '92000',
            'city' => $testData[self::CITY] ?? 'Nanterre',
            'street' => $testData[self::STREET] ?? '92 rue de Raymond Poincaré',
            'number' => $testData[self::NUMBER] ?? '',
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $testData[self::PHONE] ?? '04.94.36.42.48',
            'pickup_street' => $testData[self::PICKUP_STREET] ?? 'Rte des fusilles de la resis',
            'pickup_city' => $testData[self::PICKUP_CITY] ?? 'Nanterre',
            'pickup_number' => $testData[self::PICKUP_NUMBER] ?? '110',
            'pickup_postal_code' => $testData[self::PICKUP_POSTAL_CODE] ?? '92000',
            'pickup_country' => $testData[self::PICKUP_COUNTRY] ?? 'FR',
            'pickup_location_name' => $testData[self::PICKUP_LOCATION_NAME] ?? 'MARCHE D A COTE',
            'pickup_location_code' => $testData[self::PICKUP_LOCATION_CODE] ?? 'S2203',
            'retail_network_id' => $testData[self::RETAIL_NETWORK_ID] ?? '',
        ]);
        
        // Set up mock expectations for each response from the dataset
        foreach ($responses as $response) {
            $mockCurl->shouldReceive('write')
                ->once()
                ->with(\Mockery::type('string'), \Mockery::type('string'), \Mockery::type('array'), \Mockery::type('string'))
                ->andReturn('');
            $mockCurl->shouldReceive('getResponse')
                ->once()
                ->andReturn($response);
            $mockCurl->shouldReceive('close')
                ->once()
                ->andReturnSelf();
        }

        $this->doConsignmentTest($testData);
    }

    /**
     * @return array|string[]
     * @throws \Exception
     */
    protected function getDefaultConsignmentData(): array
    {
        return array_replace(
            parent::getDefaultConsignmentData(),
            [
                self::CARRIER_ID  => CarrierDHLParcelConnect::ID,
                self::COUNTRY     => 'FR',
                self::FULL_STREET => '92 rue de Raymond Poincaré',
                self::POSTAL_CODE => '92000',
                self::CITY        => 'Nanterre',
                self::PHONE       => '04.94.36.42.48',
            ]
        );
    }
}
