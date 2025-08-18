<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierUPSStandard;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class UPSStandardConsignmentTest extends ConsignmentTestCase
{
    public const  PICKUP_LOCATION_CODE = 'pickup_location_code';

    /**
     * @return array
     * @throws \Exception
     */
    public function provideUPSStandardConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'NL -> EU' => [],
            'NL -> NL' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::WEIGHT => 1000,
            ],
            'NL -> NL with age check' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::AGE_CHECK => true,
                self::WEIGHT => 1000,
                self::expected(self::SIGNATURE) => true,
            ],
            'NL -> NL with saturday delivery' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::WEIGHT => 1000,
                self::EXTRA_OPTIONS => [
                    AbstractConsignment::EXTRA_OPTION_DELIVERY_SATURDAY => true,
                ],
            ],
            'UPS Pickup location test' => [
                self::COUNTRY              => AbstractConsignment::CC_NL,
                self::POSTAL_CODE          => '1055NB',
                self::CITY                 => 'Amsterdam',
                self::FULL_STREET          => 'Kerkstraat 358 AH',
                self::DELIVERY_TYPE        => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_CITY          => 'Amsterdam',
                self::PICKUP_COUNTRY       => AbstractConsignment::CC_NL,
                self::PICKUP_LOCATION_NAME => 'Tabakspeciaalzaak Admiraal',
                self::PICKUP_NUMBER        => '389',
                self::PICKUP_POSTAL_CODE   => '1055MC',
                self::PICKUP_STREET        => 'Admiraal De Ruijterweg',
                self::RETAIL_NETWORK_ID    => '',
                self::PICKUP_LOCATION_CODE => 'U42446260',
            ],
            'UPS Pickup location with missing required fields' => [
                self::COUNTRY              => AbstractConsignment::CC_NL,
                self::POSTAL_CODE          => '1055NB',
                self::CITY                 => 'Amsterdam',
                self::FULL_STREET          => 'Kerkstraat 358 AH',
                self::DELIVERY_TYPE        => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_LOCATION_CODE => 'U42446260',
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                self::expected(self::PICKUP_LOCATION_CODE) => '',
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideUPSStandardConsignmentsData
     */
    public function testUPSStandardConsignments(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Parse full street if provided, otherwise use individual street/number
        $fullStreet = $testData[self::FULL_STREET] ?? null;
        $street = $testData[self::STREET] ?? 'Feldstrasse';
        $number = $testData[self::NUMBER] ?? '17';
        
        if ($fullStreet) {
            // Extract street and number from full street
            $parts = preg_split('/\s+/', trim($fullStreet));
            $number = array_pop($parts); // Get the last part as number
            $street = implode(' ', $parts); // Join the rest as street
        }
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getUPSStandardFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'age_check' => $testData[self::AGE_CHECK] ?? false,
            'delivery_type' => $testData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_STANDARD,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'DE',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '39394',
            'city' => $testData[self::CITY] ?? 'Schwanebeck',
            'street' => $street,
            'number' => $number,
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $testData[self::PHONE] ?? '0612345678',
            'weight' => $testData[self::WEIGHT] ?? 1000,
            'extra_options' => $testData[self::EXTRA_OPTIONS] ?? [],
            'pickup_street' => $testData[self::PICKUP_STREET] ?? null,
            'pickup_city' => $testData[self::PICKUP_CITY] ?? null,
            'pickup_number' => $testData[self::PICKUP_NUMBER] ?? null,
            'pickup_postal_code' => $testData[self::PICKUP_POSTAL_CODE] ?? null,
            'pickup_country' => $testData[self::PICKUP_COUNTRY] ?? null,
            'pickup_location_name' => $testData[self::PICKUP_LOCATION_NAME] ?? null,
            'pickup_location_code' => $testData[self::PICKUP_LOCATION_CODE] ?? null,
            'retail_network_id' => $testData[self::RETAIL_NETWORK_ID] ?? null,
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
                self::CARRIER_ID   => CarrierUPSStandard::ID,
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                self::FULL_STREET  => 'Feldstrasse 17',
                self::POSTAL_CODE  => '39394',
                self::CITY         => 'Schwanebeck',
                self::COUNTRY      => 'DE',
                self::PHONE        => '0612345678',
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                self::WEIGHT => 1000,
            ]
        );
    }
}
