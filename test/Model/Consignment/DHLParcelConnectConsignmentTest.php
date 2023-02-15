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
            self::PICKUP_STREET => 'Boulevard De Courcelles',
            self::PICKUP_CITY   => 'Paris',
            self::PICKUP_NUMBER => '6',
            self::PICKUP_POSTAL_CODE => '75017',
            self::PICKUP_COUNTRY => 'FR',
            self::PICKUP_LOCATION_NAME => 'RESEAU MAC',
            self::PICKUP_LOCATION_CODE => '8015-C32T2',
            self::RETAIL_NETWORK_ID => '',
        ];

        return $this->createConsignmentProviderDataset([
            'Signature'       => [
                self::SIGNATURE => true,
            ] + $pickupInformation,
            'Insurance' => [
                self::expected(self::INSURANCE) => 0,
                self::SIGNATURE                 => true,
            ] + $pickupInformation,
            'Return'          => [
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
                self::POSTAL_CODE => '11100',
                self::CITY        => 'Languedoc-Roussillon',
                self::PHONE       => '04.94.36.42.48',
            ]
        );
    }
}
