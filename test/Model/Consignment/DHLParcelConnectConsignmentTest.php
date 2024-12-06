<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLParcelConnect;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class DHLParcelConnectConsignmentTest extends ConsignmentTestCase
{
    public const  PICKUP_LOCATION_CODE = 'pickup_location_code';
    private const LOCATION_CODE        = 'location_code';

    /**
     * @return array
     * @throws \Exception
     */
    public function provideDHLParcelConnectConsignmentsData(): array
    {
        $pickupInformation = [
            self::DELIVERY_TYPE => AbstractConsignment::DELIVERY_TYPE_PICKUP,
            self::PICKUP_STREET => 'Rte des fusilles de la resis',
            self::PICKUP_CITY   => 'Nanterre',
            self::PICKUP_NUMBER => '110',
            self::PICKUP_POSTAL_CODE => '92000',
            self::PICKUP_COUNTRY => 'FR',
            self::PICKUP_LOCATION_NAME => 'MARCHE D A COTE',
            self::PICKUP_LOCATION_CODE => 'S2203',
            self::RETAIL_NETWORK_ID => '',
        ];

        return $this->createConsignmentProviderDataset([
            'Signature' => [
                    self::SIGNATURE => true,
                ] + $pickupInformation,
            'Insurance' => [
                    self::expected(self::INSURANCE) => 0,
                    self::SIGNATURE                 => true,
                ] + $pickupInformation,
            'Return'    => [
                    self::RETURN                 => true,
                    self::expected(self::RETURN) => false,
                    self::SIGNATURE              => true,
                ] + $pickupInformation,
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideDHLParcelConnectConsignmentsData
     */
    public function testDHLParcelConnectConsignments(array $testData): void
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
                self::CARRIER_ID  => CarrierDHLParcelConnect::ID,
                self::COUNTRY     => 'FR',
                self::FULL_STREET => '92 rue de Raymond Poincaré',
                self::POSTAL_CODE => '92000',
                self::CITY        => 'Nanterre',
                self::PHONE       => '04.94.36.42.48',
            ]
        );
    }
}
