<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierTrunkrs;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class TrunkrsConsignmentTest extends ConsignmentTestCase
{

    /**
     * @return array
     * @throws \Exception
     */
    public function provideTrunkrsConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Standard evening delivery' => [],
            'Age check' => [
                self::AGE_CHECK                      => true,
                self::SIGNATURE                      => true,
                self::ONLY_RECIPIENT                 => true,
            ],
            'Receipt code' => [
                self::RECEIPT_CODE                   => true,
                self::SIGNATURE                      => true,
                self::ONLY_RECIPIENT                 => true,
            ],
            'Fresh food' => [
                self::FRESH_FOOD => true,
            ],
            'Frozen' => [
                self::FROZEN => true,
            ],
            'Same day delivery' => [
                self::SAME_DAY_DELIVERY => true,
            ],
            'All options combined' => [
                self::AGE_CHECK                      => true,
                self::SIGNATURE                      => true,
                self::ONLY_RECIPIENT                 => true,
                self::FRESH_FOOD                     => true,
                self::FROZEN                         => true,
                self::SAME_DAY_DELIVERY              => true,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideTrunkrsConsignmentsData
     */
    public function testTrunkrsConsignments(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getTrunkrsFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'age_check' => $testData[self::AGE_CHECK] ?? false,
            'signature' => $testData[self::SIGNATURE] ?? false,
            'only_recipient' => $testData[self::ONLY_RECIPIENT] ?? false,
            'receipt_code' => $testData[self::RECEIPT_CODE] ?? false,
            'fresh_food' => $testData[self::FRESH_FOOD] ?? false,
            'frozen' => $testData[self::FROZEN] ?? false,
            'same_day_delivery' => $testData[self::SAME_DAY_DELIVERY] ?? false,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'NL',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '2132JE',
            'city' => $testData[self::CITY] ?? 'Hoofddorp',
            'street' => $testData[self::STREET] ?? 'Antareslaan',
            'number' => $testData[self::NUMBER] ?? '31',
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $testData[self::PHONE] ?? '0612345678',
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
                self::CARRIER_ID       => CarrierTrunkrs::ID,
                self::DELIVERY_TYPE    => AbstractConsignment::DELIVERY_TYPE_EVENING,
                self::FULL_STREET      => 'Antareslaan 31',
                self::POSTAL_CODE      => '2132JE',
                self::CITY             => 'Hoofddorp',
                self::WEIGHT           => 1000,
            ]
        );
    }
}
