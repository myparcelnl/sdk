<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLForYou;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class DHLForYouConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideDHLForYouConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'same day delivery' => [
                self::SAME_DAY_DELIVERY => true,
            ],
            'Hide sender'       => [
                self::HIDE_SENDER       => true,
                self::SAME_DAY_DELIVERY => true,
            ],
            'Extra assurance'   => [
                self::EXTRA_ASSURANCE           => true,
                self::SAME_DAY_DELIVERY         => true,
                self::expected(self::INSURANCE) => 0,
            ],
            'Return'   => [
                self::RETURN                 => true,
                self::expected(self::RETURN) => false,
                self::SAME_DAY_DELIVERY      => true,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider provideDHLForYouConsignmentsData
     */
    public function testDHLForYouConsignments(array $testData): void
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
                self::CARRIER_ID        => CarrierDHLForYou::ID,
                self::FULL_STREET       => 'Meander 631',
                self::POSTAL_CODE       => '6825ME',
                self::CITY              => 'Arnhem',
                self::PHONE             => '123456',
            ]
        );
    }
}
