<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierUPS;
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
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideUPSConsignmentsData
     */
    public function testUPSForYouConsignments(array $testData): void
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
                self::CARRIER_ID  => CarrierUPS::ID,
                self::FULL_STREET => 'Meander 631',
                self::POSTAL_CODE => '6825ME',
                self::CITY        => 'Arnhem',
                self::PHONE       => '123456',
            ]
        );
    }
}
