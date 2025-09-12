<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierGLS;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class GLSConsignmentTest extends ConsignmentTestCase
{
    public const PICKUP_LOCATION_CODE = 'pickup_location_code';
    
    /**
     * @return array
     * @throws \Exception
     */
    public function provideGLSConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'NL -> NL Standard' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '2132JE',
                self::CITY => 'Hoofddorp',
                self::FULL_STREET => 'Antareslaan 31',
                self::expected(self::INSURANCE) => 10000,
            ],
            
            'NL -> DE (signature required)' => [
                self::COUNTRY => 'DE',
                self::POSTAL_CODE => '39394',
                self::CITY => 'Schwanebeck',
                self::FULL_STREET => 'Feldstrasse 17',
                self::SIGNATURE => true,
                self::expected(self::INSURANCE) => 10000,
            ],
            
            'NL -> NL with Saturday delivery' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::EXTRA_OPTIONS => [
                    AbstractConsignment::EXTRA_OPTION_DELIVERY_SATURDAY => true,
                ],
                self::expected(self::INSURANCE) => 10000,
            ],
            
            'NL -> NL Pickup' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '2132JE',
                self::CITY => 'Hoofddorp',
                self::FULL_STREET => 'Antareslaan 31',
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_CITY => 'Hoofddorp',
                self::PICKUP_COUNTRY => AbstractConsignment::CC_NL,
                self::PICKUP_LOCATION_NAME => 'GLS Point Test',
                self::PICKUP_NUMBER => '123',
                self::PICKUP_POSTAL_CODE => '2132AA',
                self::PICKUP_STREET => 'Teststraat',
                self::PICKUP_LOCATION_CODE => 'GLSNL-TEST01',
                self::RETAIL_NETWORK_ID => 'GLSNL-01',
                self::expected(self::INSURANCE) => 10000,
            ],

            'NL -> NL Mailbox' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '2132JE',
                self::CITY => 'Hoofddorp',
                self::FULL_STREET => 'Antareslaan 31',
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_MAILBOX,
                self::expected(self::INSURANCE) => 10000,
        ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideGLSConsignmentsData
     */
    public function testGLSConsignments(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Parse full street if provided, otherwise use individual street/number
        $fullStreet = $testData[self::FULL_STREET] ?? null;
        $street = $testData[self::STREET] ?? 'Antareslaan';
        $number = $testData[self::NUMBER] ?? '31';
        
        if ($fullStreet) {
            // Extract street and number from full street
            $parts = preg_split('/\s+/', trim($fullStreet));
            $number = array_pop($parts); // Get the last part as number
            $street = implode(' ', $parts); // Join the rest as street
        }
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getGLSFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'delivery_type' => $testData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_STANDARD,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'NL',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '2132JE',
            'city' => $testData[self::CITY] ?? 'Hoofddorp',
            'street' => $street,
            'number' => $number,
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $testData[self::PHONE] ?? '0612345678',
            'signature' => $testData[self::SIGNATURE] ?? null,
            'only_recipient' => $testData[self::ONLY_RECIPIENT] ?? false,
            'insurance' => $testData[self::INSURANCE] ?? 10000,
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
                self::CARRIER_ID => CarrierGLS::ID,
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                self::FULL_STREET => 'Antareslaan 31',
                self::POSTAL_CODE => '2132JE',
                self::CITY => 'Hoofddorp',
                self::COUNTRY => 'NL',
                self::PHONE => '0612345678',
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                self::WEIGHT => 1000,
            ]
        );
    }
}
