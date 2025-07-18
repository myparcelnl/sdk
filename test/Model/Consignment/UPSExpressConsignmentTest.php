<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierUPSExpressSaver;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class UPSExpressConsignmentTest extends ConsignmentTestCase
{
    public const  PICKUP_LOCATION_CODE = 'pickup_location_code';
    /**
     * @return array
     * @throws \Exception
     */
    public function provideUPSExpressConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'NL -> EU' => [],
            'NL -> NL' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::WEIGHT => 1000,
            ],
            'NL -> NL with age check' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::AGE_CHECK => true,
                self::WEIGHT => 1000,
                self::expected(self::SIGNATURE) => true,
            ],
            'NL -> NL with saturday delivery' => [
                self::COUNTRY => AbstractConsignment::CC_NL,
                self::POSTAL_CODE => '1234AB',
                self::CITY => 'Amsterdam',
                self::FULL_STREET => 'Hoofdstraat 1',
                self::WEIGHT => 1000,
                self::EXTRA_OPTIONS => [
                    AbstractConsignment::EXTRA_OPTION_DELIVERY_SATURDAY => true,
                ],
            ],
            'UPS Express Pickup location test' => [
                self::COUNTRY              => AbstractConsignment::CC_NL,
                self::POSTAL_CODE          => '1055NB',
                self::CITY                 => 'Amsterdam',
                self::FULL_STREET          => 'Kerkstraat 358 AH',
                self::DELIVERY_TYPE        => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_CITY          => 'Amsterdam',
                self::PICKUP_COUNTRY       => AbstractConsignment::CC_NL,
                self::PICKUP_LOCATION_NAME => 'Tabakspeciaalzaak Admiraal',
                self::PICKUP_NUMBER        => '389',
                self::PICKUP_POSTAL_CODE   => '1055MC',
                self::PICKUP_STREET        => 'Admiraal De Ruijterweg',
                self::RETAIL_NETWORK_ID    => '',
                self::PICKUP_LOCATION_CODE => 'U42446260',
            ],
            'UPS Express Pickup location with missing required fields' => [
                self::COUNTRY              => AbstractConsignment::CC_NL,
                self::POSTAL_CODE          => '1055NB',
                self::CITY                 => 'Amsterdam',
                self::FULL_STREET          => 'Kerkstraat 358 AH',
                self::DELIVERY_TYPE        => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                self::PICKUP_LOCATION_CODE => 'U42446260',
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_EXPRESS,
                self::expected(self::PICKUP_LOCATION_CODE) => '',
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideUPSExpressConsignmentsData
     */
    public function testUPSExpressConsignments(array $testData): void
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
                self::CARRIER_ID   => CarrierUPSExpressSaver::ID,
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                self::FULL_STREET  => 'Feldstrasse 17',
                self::POSTAL_CODE  => '39394',
                self::CITY         => 'Schwanebeck',
                self::COUNTRY      => 'DE',
                self::PHONE        => '0612345678',
                self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_EXPRESS,
                self::WEIGHT => 1000,
            ]
        );
    }
}
