<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class DpdConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public static function provideDpdConsignmentsData(): array
    {
        $instance = new self();
        return $instance->createConsignmentProviderDataset([
            'BE -> BE' => [],
            'BE -> NL' => $instance->getDefaultAddress(),
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideDpdConsignmentsData
     */
    public function testDpdConsignments(array $testData): void
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
                self::WEIGHT => 100,
            ]
        );
    }
}
