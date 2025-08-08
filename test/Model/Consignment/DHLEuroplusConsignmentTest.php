<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierDHLEuroplus;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\MockMyParcelCurl;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class DHLEuroplusConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideDHLEuroPlusConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Signature' => [
                self::SIGNATURE => true,
            ],
            'Insurance' => [
                self::INSURANCE                 => 0,
                self::expected(self::INSURANCE) => 0,
                self::SIGNATURE                 => true,
            ],
            'Return'    => [
                self::RETURN                 => true,
                self::expected(self::RETURN) => false,
                self::SIGNATURE              => true,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideDHLEuroPlusConsignmentsData
     */
    public function testDHLEuroPlusConsignments(array $testData): void
    {
        // Clear and prepare mock responses using dataset
        MockMyParcelCurl::$responseQueue = [];
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getDHLEuroplusFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'signature' => $testData[self::SIGNATURE] ?? false,
            'insurance' => $testData[self::INSURANCE] ?? 0,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? 1,
            'country' => $testData[self::COUNTRY] ?? 'DE',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '39394',
            'city' => $testData[self::CITY] ?? 'Schwanebeck',
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? 'spam@myparcel.nl',
            'phone' => $testData[self::PHONE] ?? '123456',
        ]);
        
        // Queue all responses
        foreach ($responses as $response) {
            MockMyParcelCurl::addResponse($response);
        }
        
        // Run the actual test
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
                self::CARRIER_ID  => CarrierDHLEuroplus::ID,
                self::FULL_STREET => 'Feldstrasse 17',
                self::POSTAL_CODE => '39394',
                self::CITY        => 'Schwanebeck',
                self::COUNTRY     => 'DE',
                self::PHONE       => '123456',
            ]
        );
    }
}
