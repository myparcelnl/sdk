<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\PrinterlessReturnRequest;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;

class   ConsignmentShipmentOptionsTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provide18PlusCheckData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Normal 18+ check'      => [
                self::AGE_CHECK      => true,
                self::ONLY_RECIPIENT => true,
                self::SIGNATURE      => true,
            ],
            // todo:
            //  '18+ check no signature' => [
            //      self::AGE_CHECK                      => true,
            //      self::ONLY_RECIPIENT                 => false,
            //      self::SIGNATURE                      => false,
            //      self::expected(self::ONLY_RECIPIENT) => true,
            //      self::expected(self::SIGNATURE)      => true,
            //  ],
            '18+ check EU shipment' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::AGE_CHECK => true,
                    self::EXCEPTION => 'The age check is not possible with an EU shipment or world shipment',
                ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideDeliveryMomentData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Morning delivery'                => [
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_MORNING,
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
            ],
            'Morning delivery with signature' => [
                self::SIGNATURE     => true,
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_MORNING,
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
            ],
            'Evening delivery'                => [
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_EVENING,
            ],
            'Evening delivery with signature' => [
                self::SIGNATURE     => true,
                self::DELIVERY_DATE => $this->generateDeliveryDate(),
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_EVENING,
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideDigitalStampData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Digital stamp 80 grams' => [
                self::LABEL_DESCRIPTION => 112345,
                self::PACKAGE_TYPE      => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
                self::TOTAL_WEIGHT      => 76,
            ],
            'Digital stamp 2kg'      => [
                self::LABEL_DESCRIPTION => 112345,
                self::PACKAGE_TYPE      => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
                self::TOTAL_WEIGHT      => 1999,
            ],
            // todo:
            //  'Digital stamp no weight' => [
            //      self::LABEL_DESCRIPTION            => 112345,
            //      self::PACKAGE_TYPE                 => AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
            //      self::TOTAL_WEIGHT                 => 0,
            //      self::expected(self::TOTAL_WEIGHT) => 1,
            //  ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideLargeFormatData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Large format national'   => [
                self::CUSTOMS_DECLARATION => $this->getDefaultCustomsDeclaration(),
                self::LARGE_FORMAT        => true,
            ],
            // todo:
            //  'Large format set from true to false' => $this->getDefaultAddress('CA') + [
            //          self::CUSTOMS_DECLARATION          => $this->getDefaultCustomsDeclaration('CA'),
            //          self::LARGE_FORMAT                 => true,
            //          self::expected(self::LARGE_FORMAT) => false,
            //      ],
            'Large format to Belgium' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::CUSTOMS_DECLARATION       => $this->getDefaultCustomsDeclaration(
                        AbstractConsignment::CC_BE
                    ),
                    self::LARGE_FORMAT                   => true,
                    self::expected(self::INSURANCE)      => 0,
                    self::expected(self::ONLY_RECIPIENT) => false,
                    self::expected(self::SIGNATURE)      => false,
                ],
        ]);
    }

    /**
     * @throws \Exception
     */
    public function provideShipmentOptionsWithPickupData(): array
    {
        return $this->createConsignmentProviderDataset(
            [
                'Pickup with shipment options PostNL'     => [
                    self::DELIVERY_DATE                  => $this->generateDeliveryDate(),
                    self::ONLY_RECIPIENT                 => true,
                    self::RETURN                         => true,
                    self::SIGNATURE                      => true,
                    self::DELIVERY_TYPE                  => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                    self::expected(self::ONLY_RECIPIENT) => false,
                    self::expected(self::RETURN)         => false,
                    self::expected(self::SIGNATURE)      => true,
                ],
            ]
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideMailboxData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Mailbox shipment' => [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_MAILBOX,
            ],
            'Mailbox with shipment options' => [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_MAILBOX,
                self::INSURANCE    => 250,
                self::expected(self::INSURANCE)      => 0,
                self::expected(self::LARGE_FORMAT)   => false,
                self::expected(self::ONLY_RECIPIENT) => false,
                self::expected(self::RETURN)         => false,
                self::expected(self::SIGNATURE)      => false,
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideInsuranceData(): array
    {
        return $this->createConsignmentProviderDataset([
            'EUR 250' => [
                self::INSURANCE                      => (int) 250,  // Explicitly cast to integer
                self::expected(self::ONLY_RECIPIENT) => true,
                self::expected(self::SIGNATURE) => true,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideInsuranceData
     */
    public function testInsurance(array $testData): void
    {
        $mockCurl = $this->mockCurl();

        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'insurance' => (int)($testData[self::INSURANCE] ?? 0),
            'only_recipient' => $testData[self::ONLY_RECIPIENT] ?? false,
            'signature' => $testData[self::SIGNATURE] ?? false,
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

        // Modify the responses to ensure insurance is an integer in the mock data
        // This avoids the ConsignmentAdapter converting it to float
        foreach ($responses as &$response) {
            if (isset($response['response'])) {
                $decodedResponse = json_decode($response['response'], true);
                if (isset($decodedResponse['data']['shipments'])) {
                    foreach ($decodedResponse['data']['shipments'] as &$shipment) {
                        if (isset($shipment['options']['insurance']['amount'])) {
                            // Ensure insurance amount is integer and multiply by 100 since adapter divides by 100
                            $shipment['options']['insurance']['amount'] = (int)($testData[self::INSURANCE] ?? 0) * 100;
                        }
                        // When insurance is set, signature and only_recipient should be automatically enabled
                        if ((int)($testData[self::INSURANCE] ?? 0) > 0) {
                            $shipment['options']['signature'] = true;
                            $shipment['options']['only_recipient'] = true;
                        }
                    }
                    $response['response'] = json_encode($decodedResponse);
                }
            }
        }
        unset($response);

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
     * @return array
     * @throws \Exception
     */
    public function providePickupLocationData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Pickup location' => [
                self::DELIVERY_DATE        => $this->generateDeliveryDate(),
                self::DELIVERY_TYPE        => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_CITY          => 'Hoofddorp',
                self::PICKUP_COUNTRY       => AbstractConsignment::CC_NL,
                self::PICKUP_LOCATION_NAME => 'Primera Sanders',
                self::PICKUP_NUMBER        => '1',
                self::PICKUP_POSTAL_CODE   => '2132BA',
                self::PICKUP_STREET        => 'Polderplein',
                self::RETAIL_NETWORK_ID    => 'PNPNL-01',
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideReferenceIdentifierData(): array
    {
        return $this->createConsignmentProviderDataset([
            //            'normal consignment with reference id'        => [
            //                [self::REFERENCE_IDENTIFIER => $this->generateTimestamp() . '_normal_consignment'],
            //            ],
            'two consignments with reference identifiers' => [
                [self::REFERENCE_IDENTIFIER => $this->generateTimestamp() . '_2_1'],
                [self::REFERENCE_IDENTIFIER => $this->generateTimestamp() . '_2_2'],
            ],
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideUnrelatedReturnData(): array
    {
        return $this->createConsignmentProviderDataset(
            [
                'unrelated return'   => [
                    self::DELIVERY_DATE => $this->generateDeliveryDate(),
                ],
                'printerless return' => [
                    self::DELIVERY_DATE  => $this->generateDeliveryDate(),
                    'printerless_return' => true,
                ],
            ]
        );
    }


    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provide18PlusCheckData
     */
    public function test18PlusCheck(array $testData): void
    {
        $consignmentData = $testData[0] ?? $testData;

        // Skip mocking if we expect an exception
        if (!isset($consignmentData[self::EXCEPTION])) {
            // Mock HTTP client for API calls
            $mockCurl = $this->mockCurl();

            // Get the appropriate response set from the dataset
            $responses = ShipmentResponses::getPostNLFlow([
                'reference_identifier' => $consignmentData[self::REFERENCE_IDENTIFIER] ?? null,
                'age_check' => $consignmentData[self::AGE_CHECK] ?? false,
                'only_recipient' => $consignmentData[self::ONLY_RECIPIENT] ?? false,
                'signature' => $consignmentData[self::SIGNATURE] ?? false,
                'package_type' => $consignmentData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                'country' => $consignmentData[self::COUNTRY] ?? 'NL',
                'postal_code' => $consignmentData[self::POSTAL_CODE] ?? '1012AB',
                'city' => $consignmentData[self::CITY] ?? 'Amsterdam',
                'street' => $consignmentData[self::STREET] ?? 'Antareslaan',
                'number' => $consignmentData[self::NUMBER] ?? '31',
                'person' => $consignmentData[self::PERSON] ?? 'Test Person',
                'company' => $consignmentData[self::COMPANY] ?? 'MyParcel',
                'email' => $consignmentData[self::EMAIL] ?? 'test@myparcel.nl',
                'phone' => $consignmentData[self::PHONE] ?? '0612345678',
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
        }

        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideDigitalStampData
     */
    public function testDigitalStamp(array $testData): void
    {
        $mockCurl = $this->mockCurl();

        $weight = (int)($testData[self::TOTAL_WEIGHT] ?? 76);

        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'label_description'    => $testData[self::LABEL_DESCRIPTION] ?? null,
            'package_type'         => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP,
            'country'     => $testData[self::COUNTRY] ?? 'NL',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '1012AB',
            'city'        => $testData[self::CITY] ?? 'Amsterdam',
            'street'      => $testData[self::STREET] ?? 'Antareslaan',
            'number'      => $testData[self::NUMBER] ?? '31',
            'person'      => $testData[self::PERSON] ?? 'Test Person',
            'company'     => $testData[self::COMPANY] ?? 'MyParcel',
            'email'       => $testData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone'       => $testData[self::PHONE] ?? '0612345678',
        ]);


        foreach ($responses as &$resp) {
            if (! isset($resp['response'])) {
                continue;
            }
            $payload = json_decode($resp['response'], true);
            if (isset($payload['data']['shipments'][0])) {
                $payload['data']['shipments'][0]['physical_properties']['weight'] = $weight;
                $resp['response'] = json_encode($payload);
                break;
            }
        }
        unset($resp);

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
     * @throws \Exception
     * @dataProvider provideLargeFormatData
     */
    public function testLargeFormat(array $testData): void
    {
        $consignmentData = $testData[0] ?? $testData;

        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();

        // Parse full street if provided, otherwise use individual street/number
        $fullStreet = $consignmentData[self::FULL_STREET] ?? null;
        $street = $consignmentData[self::STREET] ?? 'Antareslaan';
        $number = $consignmentData[self::NUMBER] ?? '31';

        if ($fullStreet) {
            // Extract street and number from full street
            $parts = preg_split('/\s+/', trim($fullStreet));
            $number = array_pop($parts); // Get the last part as number
            $street = implode(' ', $parts); // Join the rest as street
        }

        // Get the appropriate response set from the dataset - PostNL can ship to Belgium too
        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $consignmentData[self::REFERENCE_IDENTIFIER] ?? null,
            'customs_declaration' => $consignmentData[self::CUSTOMS_DECLARATION] ?? null,
            'large_format' => $consignmentData[self::LARGE_FORMAT] ?? false,
            'package_type' => $consignmentData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $consignmentData[self::COUNTRY] ?? 'NL',
            'postal_code' => $consignmentData[self::POSTAL_CODE] ?? '2132JE',
            'city' => $consignmentData[self::CITY] ?? 'Hoofddorp',
            'street' => $street,
            'number' => $number,
            'person' => $consignmentData[self::PERSON] ?? 'Test Person',
            'company' => $consignmentData[self::COMPANY] ?? 'MyParcel',
            'email' => $consignmentData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $consignmentData[self::PHONE] ?? '0612345678',
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
     * @throws \Exception
     * @dataProvider provideShipmentOptionsWithPickupData
     */
    public function testPickupWithOptions(array $testData): void
    {
        $consignmentData = $testData[0] ?? $testData;

        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();

        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $consignmentData[self::REFERENCE_IDENTIFIER] ?? null,
            'delivery_date' => $consignmentData[self::DELIVERY_DATE] ?? null,
            'only_recipient' => $consignmentData[self::ONLY_RECIPIENT] ?? false,
            'return' => $consignmentData[self::RETURN] ?? false,
            'signature' => $consignmentData[self::SIGNATURE] ?? false,
            'delivery_type' => $consignmentData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_PICKUP,
            'package_type' => $consignmentData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $consignmentData[self::COUNTRY] ?? 'NL',
            'postal_code' => $consignmentData[self::POSTAL_CODE] ?? '1012AB',
            'city' => $consignmentData[self::CITY] ?? 'Amsterdam',
            'street' => $consignmentData[self::STREET] ?? 'Antareslaan',
            'number' => $consignmentData[self::NUMBER] ?? '31',
            'person' => $consignmentData[self::PERSON] ?? 'Test Person',
            'company' => $consignmentData[self::COMPANY] ?? 'MyParcel',
            'email' => $consignmentData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $consignmentData[self::PHONE] ?? '0612345678',
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
     * @throws \Exception
     * @dataProvider provideMailboxData
     */
    public function testMailbox(array $testData): void
    {
        $consignmentData = $testData[0] ?? $testData;
        
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();

        // Get the appropriate response set from the dataset using values from test data
        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $consignmentData[self::REFERENCE_IDENTIFIER] ?? null,
            'insurance' => (int)($consignmentData[self::INSURANCE] ?? 0),
            'label_description' => $consignmentData[self::LABEL_DESCRIPTION] ?? null,
            'large_format' => $consignmentData[self::LARGE_FORMAT] ?? false,
            'only_recipient' => $consignmentData[self::ONLY_RECIPIENT] ?? false,
            'return' => $consignmentData[self::RETURN] ?? false,
            'signature' => $consignmentData[self::SIGNATURE] ?? false,
            'package_type' => $consignmentData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_MAILBOX,
            'country' => $consignmentData[self::COUNTRY] ?? 'NL',
            'postal_code' => $consignmentData[self::POSTAL_CODE] ?? '2132JE',
            'city' => $consignmentData[self::CITY] ?? 'Hoofddorp',
            'street' => $consignmentData[self::STREET] ?? 'Antareslaan',
            'number' => $consignmentData[self::NUMBER] ?? '31',
            'person' => $consignmentData[self::PERSON] ?? 'Test Person',
            'company' => $consignmentData[self::COMPANY] ?? 'MyParcel',
            'email' => $consignmentData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone' => $consignmentData[self::PHONE] ?? '0612345678',
        ]);

        // Ensure insurance amounts are integers in mock responses
        foreach ($responses as &$response) {
            if (isset($response['response'])) {
                $decodedResponse = json_decode($response['response'], true);
                if (isset($decodedResponse['data']['shipments'])) {
                    foreach ($decodedResponse['data']['shipments'] as &$shipment) {
                        if (isset($shipment['options']['insurance']['amount'])) {
                            // Mailbox packages don't support insurance so set it to 0
                            $shipment['options']['insurance']['amount'] = 0;
                        }
                    }
                    $response['response'] = json_encode($decodedResponse);
                }
            }
        }
        unset($response);

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
     * @throws \Exception
     * @dataProvider providePickupLocationData
     */
    public function testPickupLocation(array $testData): void
    {
        $consignmentData = $testData[0] ?? $testData;

        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();

        $pickup = [
            'city'              => $consignmentData[self::PICKUP_CITY] ?? 'Hoofddorp',
            'cc'                => $consignmentData[self::PICKUP_COUNTRY] ?? 'NL',
            'location_name'     => $consignmentData[self::PICKUP_LOCATION_NAME] ?? 'Primera Sanders',
            'number'            => $consignmentData[self::PICKUP_NUMBER] ?? '1',
            'postal_code'       => $consignmentData[self::PICKUP_POSTAL_CODE] ?? '2132BA',
            'street'            => $consignmentData[self::PICKUP_STREET] ?? 'Polderplein',
            'retail_network_id' => $consignmentData[self::RETAIL_NETWORK_ID] ?? 'PNPNL-01',
            'location_code'     => 'PUP123',
        ];

        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $consignmentData[self::REFERENCE_IDENTIFIER] ?? null,
            'delivery_date'        => $consignmentData[self::DELIVERY_DATE] ?? null,
            'delivery_type'        => $consignmentData[self::DELIVERY_TYPE] ?? AbstractConsignment::DELIVERY_TYPE_PICKUP,
            'package_type'         => $consignmentData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,

            'country'     => $consignmentData[self::COUNTRY] ?? 'NL',
            'postal_code' => $consignmentData[self::POSTAL_CODE] ?? '1012AB',
            'city'        => $consignmentData[self::CITY] ?? 'Amsterdam',
            'street'      => $consignmentData[self::STREET] ?? 'Antareslaan',
            'number'      => $consignmentData[self::NUMBER] ?? '31',
            'person'      => $consignmentData[self::PERSON] ?? 'Test Person',
            'company'     => $consignmentData[self::COMPANY] ?? 'MyParcel',
            'email'       => $consignmentData[self::EMAIL] ?? 'test@myparcel.nl',
            'phone'       => $consignmentData[self::PHONE] ?? '0612345678',
        ]);

        foreach ($responses as &$resp) {
            if (! isset($resp['response'])) {
                continue;
            }
            $payload = json_decode($resp['response'], true);
            if (isset($payload['data']['shipments'][0])) {
                $payload['data']['shipments'][0]['pickup'] = $pickup;
                $resp['response'] = json_encode($payload);
                break;
            }
        }
        unset($resp);

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
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideReferenceIdentifierData
     */
    public function testReferenceIdentifier(array $testData): void
    {
        
        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();

        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
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
     * @param  array $testData
     *
     * @return void
     * @dataProvider provideUnrelatedReturnData
     */
    public function testUnrelatedReturn(array $testData): void
    {
        $consignmentData = $testData[0] ?? $testData;

        if (!isset($consignmentData['printerless_return'])) {
            $this->expectException(ApiException::class);
            $this->expectExceptionMessage('3759 - Shipment does not have a printerless return label');
        }

        // Mock HTTP client for API calls
        $mockCurl = $this->mockCurl();

        if (isset($consignmentData['printerless_return'])) {
            // Mock responses for successful printerless return case
            $responses = [
                // Create unrelated returns response
                ShipmentResponses::createShipmentResponse(12345678, $consignmentData[self::REFERENCE_IDENTIFIER] ?? 'test-ref'),
                // PNG image response for printerless return - need to mock the specific GET request
                [
                    'response' => base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='), // Minimal PNG
                    'code' => 200
                ]
            ];
        } else {
            // Mock responses for error case
            $responses = [
                ShipmentResponses::createShipmentResponse(12345678, $consignmentData[self::REFERENCE_IDENTIFIER] ?? 'test-ref'),
                ShipmentResponses::errorResponse(3759, 'Shipment does not have a printerless return label', 422)
            ];
        }

        // Set up mock expectations - first for the createUnrelatedReturns call
        $mockCurl->shouldReceive('write')
            ->once()
            ->with(\Mockery::type('string'), \Mockery::type('string'), \Mockery::type('array'), \Mockery::type('string'))
            ->andReturn('');
        $mockCurl->shouldReceive('getResponse')
            ->once()
            ->andReturn($responses[0]);
        $mockCurl->shouldReceive('close')
            ->once()
            ->andReturnSelf();

        // Then for the PrinterlessReturnRequest->send() call - this uses a different endpoint
        $mockCurl->shouldReceive('write')
            ->once()
            ->with('GET', \Mockery::type('string'), \Mockery::type('array'), null)
            ->andReturn('');
        $mockCurl->shouldReceive('getResponse')
            ->once()
            ->andReturn($responses[1]);
        $mockCurl->shouldReceive('close')
            ->once()
            ->andReturnSelf();

        $collection = $this->generateCollection($testData);
        $collection->createUnrelatedReturns();

        self::assertEquals(1, $collection->count());

        foreach ($collection->getConsignments() as $consignment) {
            $response = (new PrinterlessReturnRequest($consignment))->send();
            self::assertIsString($response); // this is a png image
        }
    }
}
