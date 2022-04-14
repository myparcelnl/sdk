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
            'NL -> NL'          => [],
            'same day delivery' => [
                self::SAME_DAY_DELIVERY => false,
                // Delivery date is today at 14:00, it's currently 0:00 so before the max cutoff time of 9:30.
                self::DELIVERY_DATE     => $this->generateDeliveryDate('PT14H'),
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
                self::CARRIER_ID        => CarrierInstabox::ID,
                self::ADD_DROPOFF_POINT => true,
                self::FULL_STREET       => 'Meander 631',
                self::POSTAL_CODE       => '6825ME',
                self::CITY              => 'Arnhem',
                self::PHONE             => '123456',
            ]
        );
    }
}
