<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierDHLForYou;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class DHLForYouConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideDHLForYouConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'same day delivery' => [
                self::SAME_DAY_DELIVERY => true,
            ],
            'Hide sender'       => [
                self::HIDE_SENDER       => true,
                self::SAME_DAY_DELIVERY => true,
            ],
            'Insurance'         => [
                self::INSURANCE                 => 500,
                self::SAME_DAY_DELIVERY         => true,
                self::expected(self::INSURANCE) => 500,
            ],
            'Return'            => [
                self::RETURN                 => true,
                self::expected(self::RETURN) => false,
                self::SAME_DAY_DELIVERY      => true,
            ],
            'Age check'         => [
                self::AGE_CHECK                      => true,
                self::ONLY_RECIPIENT                 => true,
                self::expected(self::ONLY_RECIPIENT) => false,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideDHLForYouConsignmentsData
     */
    public function testDHLForYouConsignments(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getDHLForYouFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'same_day_delivery' => $testData[self::SAME_DAY_DELIVERY] ?? false,
            'hide_sender' => $testData[self::HIDE_SENDER] ?? false,
            'insurance' => $testData[self::INSURANCE] ?? 0,
            'return' => $testData[self::RETURN] ?? false,
            'age_check' => $testData[self::AGE_CHECK] ?? false,
            'only_recipient' => $testData[self::ONLY_RECIPIENT] ?? false,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? 1,
            'country' => $testData[self::COUNTRY] ?? 'NL',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '6825ME',
            'city' => $testData[self::CITY] ?? 'Arnhem',
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? 'spam@myparcel.nl',
            'phone' => $testData[self::PHONE] ?? '123456',
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
                self::CARRIER_ID  => CarrierDHLForYou::ID,
                self::FULL_STREET => 'Meander 631',
                self::POSTAL_CODE => '6825ME',
                self::CITY        => 'Arnhem',
                self::PHONE       => '123456',
            ]
        );
    }
}
