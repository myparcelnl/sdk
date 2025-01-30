<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierDHLForYou;
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
            'Insurance'         => [
                self::INSURANCE                 => 500,
                self::SAME_DAY_DELIVERY         => true,
                self::expected(self::INSURANCE) => 500,
            ],
            'Return'            => [
                self::RETURN                 => true,
                self::expected(self::RETURN) => false,
                self::SAME_DAY_DELIVERY      => true,
            ],
            'Age check'         => [
                self::AGE_CHECK                      => true,
                self::ONLY_RECIPIENT                 => true,
                self::expected(self::ONLY_RECIPIENT) => false,
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
        $this->markTestSkipped('Skip because of DHL API error');
        // $this->doConsignmentTest($testData);
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
                self::CARRIER_ID  => CarrierDHLForYou::ID,
                self::FULL_STREET => 'Meander 631',
                self::POSTAL_CODE => '6825ME',
                self::CITY        => 'Arnhem',
                self::PHONE       => '123456',
            ]
        );
    }
}
