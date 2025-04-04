<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierUPSStandard;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class UPSStandardConsignmentTest extends ConsignmentTestCase
{
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
            ],
            'NL -> NL with age check' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::AGE_CHECK => true,
                self::expected(self::SIGNATURE) => true,
            ],
            'NL -> NL with saturday delivery' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::EXTRA_OPTIONS => [
                    AbstractConsignment::EXTRA_OPTION_DELIVERY_SATURDAY => true,
                ],
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
                self::PHONE        => '123456',
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_STANDARD,
            ]
        );
    }
} 