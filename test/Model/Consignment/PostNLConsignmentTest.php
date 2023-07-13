<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class PostNLConsignmentTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function providePostNLConsignmentsData(): array
    {
        return $this->createConsignmentProviderDataset([
            'NL -> NL' => [],
            'NL -> BE' => $this->getDefaultAddress(AbstractConsignment::CC_BE),
            'BE -> BE' => $this->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::API_KEY => $this->getApiKey(self::ENV_API_KEY_BE),
                ],
            'AgeCheck' => [
                self::AGE_CHECK => true,
                self::expected(self::ONLY_RECIPIENT) => true,
                self::expected(self::SIGNATURE)      => true,
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @throws \Exception
     * @dataProvider providePostNLConsignmentsData
     */
    public function testPostNLConsignments(array $testData): void
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
                self::CARRIER_ID => CarrierPostNL::ID,
            ]
        );
    }
}
