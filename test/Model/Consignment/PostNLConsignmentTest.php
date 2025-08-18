<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;
class PostNLConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function providePostNLConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'NL -> NL' => [],
            'NL -> BE' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::expected(self::INSURANCE) => 0,
                    self::expected(self::ONLY_RECIPIENT) => false,
                    self::expected(self::SIGNATURE)      => false,
                ],
            'BE -> BE' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::API_KEY => $this->getApiKey(self::ENV_API_KEY_BE),
                ],
            'AgeCheck' => [
                self::AGE_CHECK => true,
                self::expected(self::ONLY_RECIPIENT) => true,
                self::expected(self::SIGNATURE)      => true,
            ],
            'Letter' => [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_LETTER,
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
            ],
            'Small package' => $this->getDefaultAddress('DE') + [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE_SMALL,
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
            ],
            'Customs declaration' => array_merge($this->getDefaultConsignmentData(), $this->getDefaultAddress('CA'), [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                self::CUSTOMS_DECLARATION => $this->getDefaultCustomsDeclaration(),
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                ]),
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider providePostNLConsignmentsData
     */
    public function testPostNLConsignments(array $testData): void
    {
        // Parse FULL_STREET into street and number if needed
        $street = $testData[self::STREET] ?? 'Antareslaan';
        $number = $testData[self::NUMBER] ?? '31';
        
        if (isset($testData[self::FULL_STREET]) && !isset($testData[self::STREET])) {
            // Split 'Adriaan Brouwerstraat 16' into 'Adriaan Brouwerstraat' and '16'
            $fullStreet = $testData[self::FULL_STREET];
            $parts = preg_split('/\s+(?=\d)/', $fullStreet, 2); // Split on space before number
            $street = $parts[0] ?? $street;
            $number = $parts[1] ?? $number;
        }
        
        // Apply PostNL business logic
        $ageCheck = $testData[self::AGE_CHECK] ?? false;
        $signature = $testData[self::SIGNATURE] ?? false;
        $onlyRecipient = $testData[self::ONLY_RECIPIENT] ?? false;
        
        // PostNL rule: Age check requires signature and only_recipient
        if ($ageCheck) {
            $signature = true;
            $onlyRecipient = true;
        }
        
        // Get the appropriate response set from the dataset
        $responses = ShipmentResponses::getPostNLFlow([
            'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
            'age_check' => $ageCheck,
            'insurance' => $testData[self::INSURANCE] ?? 0,
            'package_type' => $testData[self::PACKAGE_TYPE] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'country' => $testData[self::COUNTRY] ?? 'NL',
            'postal_code' => $testData[self::POSTAL_CODE] ?? '1012AB',
            'city' => $testData[self::CITY] ?? 'Amsterdam',
            'street' => $street,
            'number' => $number,
            'person' => $testData[self::PERSON] ?? 'Test Person',
            'company' => $testData[self::COMPANY] ?? 'MyParcel',
            'email' => $testData[self::EMAIL] ?? '',
            'phone' => $testData[self::PHONE] ?? '',
            'signature' => $signature,
            'only_recipient' => $onlyRecipient,
            'return' => $testData[self::RETURN] ?? false,
            'large_format' => $testData[self::LARGE_FORMAT] ?? false,
        ]);

        // Create mock curl client using parent helper method
        $mockCurl = $this->mockCurl();
        
        // Set up expectations for each HTTP call
        foreach ($responses as $response) {
            $mockCurl->shouldReceive('write')
                     ->once()
                     ->andReturnSelf();
                     
            $mockCurl->shouldReceive('getResponse')
                     ->once()
                     ->andReturn($response);
                     
            $mockCurl->shouldReceive('close')
                     ->once()
                     ->andReturnSelf();
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
                self::CARRIER_ID => CarrierPostNL::ID,
            ]
        );
    }
}
