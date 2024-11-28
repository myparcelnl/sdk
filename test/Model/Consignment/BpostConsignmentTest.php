<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class BpostConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public static function provideBpostConsignmentsData(): array
    {
        $instance = new self();
        return $instance->createConsignmentProviderDataset([
            'BE -> BE' => [],
            'BE -> NL' => $instance->getDefaultAddress(),
            'Bpost pickup + shipment options' => $instance->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::ONLY_RECIPIENT                 => true,
                    self::SIGNATURE                      => true,
                    self::DELIVERY_TYPE                  => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                    self::expected(self::ONLY_RECIPIENT) => false,
                    self::expected(self::SIGNATURE)      => false,
                ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @dataProvider       provideBpostConsignmentsData
     * @throws \Exception
     */
    public function testBpostConsignments(array $testData): void
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
            $this->getDefaultAddress(AbstractConsignment::CC_BE),
            [
                self::API_KEY => $this->getApiKey(self::ENV_API_KEY_BE),
                self::CARRIER_ID => CarrierDPD::ID,
                self::WEIGHT => 1500,
            ]
        );
    }
}
