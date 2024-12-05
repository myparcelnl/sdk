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
    public static function providePostNLConsignmentsData(): array
    {
        $instance = new self();
        return $instance->createConsignmentProviderDataset([
            'NL -> NL' => [],
            'NL -> BE' => $instance->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::expected(self::INSURANCE) => 0,
                    self::expected(self::ONLY_RECIPIENT) => false,
                    self::expected(self::SIGNATURE)      => false,
                ],
            'BE -> BE' => $instance->getDefaultAddress(AbstractConsignment::CC_BE) + [
                    self::API_KEY => $instance->getApiKey(self::ENV_API_KEY_BE),
                ],
            'AgeCheck' => [
                self::AGE_CHECK => true,
                self::expected(self::ONLY_RECIPIENT) => true,
                self::expected(self::SIGNATURE)      => true,
            ],
            'Letter' => [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_LETTER,
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
            ],
            'Small package' => $instance->getDefaultAddress('DE') + [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE_SMALL,
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
            ],
            'Customs declaration' => array_merge($instance->getDefaultConsignmentData(), $instance->getDefaultAddress('CA'), [
                self::PACKAGE_TYPE => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                self::CUSTOMS_DECLARATION => $instance->getDefaultCustomsDeclaration(),
                self::expected(self::DELIVERY_TYPE) => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                ]),
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
