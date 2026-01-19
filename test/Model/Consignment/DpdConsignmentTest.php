<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class DpdConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideDpdConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'BE -> BE' => [],
            'BE -> NL' => $this->getDefaultAddress(),
            'BE -> DE Pickup' => [
                self::COUNTRY => 'DE',
                self::POSTAL_CODE => '67061',
                self::CITY => 'Ludwigshafen',
                self::FULL_STREET => 'Wittelsbachstraße 46',
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_COUNTRY => 'DE',
                self::PICKUP_POSTAL_CODE => '67061',
                self::PICKUP_CITY => 'Ludwigshafen',
                self::PICKUP_STREET => 'Wittelsbachstraße',
                self::PICKUP_NUMBER => '46',
                self::PICKUP_LOCATION_NAME => 'Watan Lebensmittel',
                self::PICKUP_LOCATION_CODE => '8df1366c-fd0b-4c7e-a739-08a7197e9b70',
                self::RETAIL_NETWORK_ID => 'pickup',
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideDpdConsignmentsData
     */
    public function testDpdConsignments(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Extract street and number from full_street if needed
        $isNL = ($testData[self::COUNTRY] ?? 'BE') === 'NL';
        
        if (isset($testData[self::FULL_STREET])) {
            // Parse full_street to get street and number
            preg_match('/^(.+?)\s+(\d+.*?)$/', $testData[self::FULL_STREET], $matches);
            $street = $matches[1] ?? ($isNL ? 'Antareslaan' : 'Adriaan Brouwerstraat');
            $number = $matches[2] ?? ($isNL ? '31' : '16');
        } else {
            $street = $testData[self::STREET] ?? ($isNL ? 'Antareslaan' : 'Adriaan Brouwerstraat');
            $number = $testData[self::NUMBER] ?? ($isNL ? '31' : '16');
        }
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getDpdFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'BE',
            'postal_code' => $testData[self::POSTAL_CODE] ?? ($isNL ? '2132JE' : '2000'),
            'city' => $testData[self::CITY] ?? ($isNL ? 'Hoofddorp' : 'Antwerpen'),
            'street' => $street,
            'number' => $number,
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $testData[self::PHONE] ?? '0612345678',
            'weight' => $testData[self::WEIGHT] ?? 100,
            'delivery_type' => $testData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_STANDARD,
            'pickup_location_code' => $testData[self::PICKUP_LOCATION_CODE] ?? null,
            'pickup_location_name' => $testData[self::PICKUP_LOCATION_NAME] ?? null,
            'pickup_street' => $testData[self::PICKUP_STREET] ?? null,
            'pickup_number' => $testData[self::PICKUP_NUMBER] ?? null,
            'pickup_postal_code' => $testData[self::PICKUP_POSTAL_CODE] ?? null,
            'pickup_city' => $testData[self::PICKUP_CITY] ?? null,
            'pickup_country' => $testData[self::PICKUP_COUNTRY] ?? null,
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
            $this->getDefaultAddress(AbstractConsignment::CC_BE),
            [
                self::CARRIER_ID => CarrierDPD::ID,
                self::WEIGHT => 100,
            ]
        );
    }
}
