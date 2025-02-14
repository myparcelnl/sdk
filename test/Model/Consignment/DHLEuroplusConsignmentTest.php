<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierDHLEuroplus;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

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
