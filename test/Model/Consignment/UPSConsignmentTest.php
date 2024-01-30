<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierUPS;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class UPSConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideUPSConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'NL -> EU' => [],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideUPSConsignmentsData
     */
    public function testUPSConsignments(array $testData): void
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
                self::CARRIER_ID   => CarrierUPS::ID,
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                self::FULL_STREET  => 'Feldstrasse 17',
                self::POSTAL_CODE  => '39394',
                self::CITY         => 'Schwanebeck',
                self::COUNTRY      => 'DE',
                self::PHONE        => '123456',
            ]
        );
    }
}
