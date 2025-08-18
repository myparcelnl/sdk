<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class ConsignmentOtherOptionsTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideAutoDetectPickupData(): array
    {
        $deliveryDate = $this->generateDeliveryDate();
        return $this->createConsignmentProviderDataset(
            [
                'Auto detect pickup' => [
                    self::FULL_STREET                   => 'Aankomstpassage 4',
                    self::POSTAL_CODE                   => '1118AX',
                    self::CITY                          => 'Schiphol',
                    self::AUTO_DETECT_PICKUP            => true,
                    self::DELIVERY_DATE                 => $deliveryDate,
                    self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                    self::expected(self::DELIVERY_DATE) => $deliveryDate,
                ], [
                    self::FULL_STREET                   => 'Aankomstpassage 4',
                    self::POSTAL_CODE                   => '1118AX',
                    self::CITY                          => 'Schiphol',
                    self::AUTO_DETECT_PICKUP            => false,
                    self::DELIVERY_DATE                 => $deliveryDate,
                    self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                    self::expected(self::DELIVERY_DATE) => $deliveryDate,
                ],
            ]
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideSaveRecipientAddressData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Save recipient address' => [
                self::SAVE_RECIPIENT_ADDRESS => true,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideAutoDetectPickupData
     */
    public function testAutoDetectPickup(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'auto_detect_pickup' => $testData[self::AUTO_DETECT_PICKUP] ?? false,
            'delivery_date' => $testData[self::DELIVERY_DATE] ?? null,
            'delivery_type' => $testData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_STANDARD,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'NL',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '1118AX',
            'city' => $testData[self::CITY] ?? 'Schiphol',
            'street' => $testData[self::STREET] ?? 'Aankomstpassage',
            'number' => $testData[self::NUMBER] ?? '4',
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
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideSaveRecipientAddressData
     */
    public function testSaveRecipientAddress(array $testData): void
    {
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'save_recipient_address' => $testData[self::SAVE_RECIPIENT_ADDRESS] ?? false,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'NL',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '1012AB',
            'city' => $testData[self::CITY] ?? 'Amsterdam',
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
}
