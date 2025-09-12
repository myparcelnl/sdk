<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierBpost;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class BpostConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideBpostConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'BE -> BE' => [],
            'BE -> NL' => $this->getDefaultAddress(),
            'Bpost pickup + shipment options' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::ONLY_RECIPIENT                 => true,
                    self::SIGNATURE                      => true,
                    self::DELIVERY_TYPE                  => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                    self::expected(self::ONLY_RECIPIENT) => false,
                    self::expected(self::SIGNATURE)      => false,
                ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @dataProvider       provideBpostConsignmentsData
     * @throws \Exception
     */
    public function testBpostConsignments(array $testData): void
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
        $responses = ShipmentResponses::getBpostFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'only_recipient' => $testData[self::ONLY_RECIPIENT] ?? false,
            'signature' => $testData[self::SIGNATURE] ?? false,
            'delivery_type' => $testData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_STANDARD,
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
            'weight' => $testData[self::WEIGHT] ?? 1500,
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
                self::API_KEY => $this->getApiKey(self::ENV_API_KEY_BE),
                self::CARRIER_ID => CarrierBpost::ID,
                self::WEIGHT => 1500,
            ]
        );
    }
}
