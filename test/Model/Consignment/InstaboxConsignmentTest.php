<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierInstabox;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class InstaboxConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideRedJePakketjeConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'NL -> NL' => [
                self::ADD_DROPOFF_POINT => true,
                self::FULL_STREET       => 'Meander 631',
                self::POSTAL_CODE       => '6825ME',
                self::CITY              => 'Arnhem',
                self::PHONE             => '123456',
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideRedJePakketjeConsignmentsData
     */
    public function testRedJePakketjeConsignments(array $testData): void
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
                self::CARRIER_ID => CarrierInstabox::ID,
            ]
        );
    }
}
