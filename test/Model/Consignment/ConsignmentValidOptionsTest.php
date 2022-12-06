<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class ConsignmentValidOptionsTest extends ConsignmentTestCase
{
    public const  BASE_DATA = [
        self::CARRIER_ID => CarrierPostNL::ID,
        self::API_KEY    => 'irrelevant',
    ];
    private const CC_FR     = 'FR';

    public function provideInsurancePossibilitiesData(): array
    {
        return [
            'NL'       => [
                'data'   => self::BASE_DATA,
                'expect' => [100, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000,],
                'cc'     => AbstractConsignment::CC_NL,
            ],
            'NL -> BE' => [
                'data'   => self::BASE_DATA,
                'expect' => [500,],
                'cc'     => AbstractConsignment::CC_BE,
            ],
            'NL -> FR' => [
                'data'   => self::BASE_DATA,
                'expect' => [50, 500,],
                'cc'     => self::CC_FR,
            ],
        ];
    }

    public function provideCanHavePackageTypeData(): array
    {
        return [
            'NL' => [
                'data'   => self::BASE_DATA,
                'expect' => true,
                'assert' => AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME,
            ],
        ];
    }

    public function provideCanHaveDeliveryTypeData(): array
    {
        return [
            'Standard' => [
                'data'   => self::BASE_DATA,
                'expect' => true,
                'assert' => AbstractConsignment::DELIVERY_TYPE_STANDARD_NAME,
            ],
            'Evening'  => [
                'data'   => self::BASE_DATA + ['packageType' => AbstractConsignment::PACKAGE_TYPE_PACKAGE],
                'expect' => true,
                'assert' => AbstractConsignment::DELIVERY_TYPE_EVENING_NAME,
            ],
        ];
    }

    /**
     * @param  array  $data
     * @param  array  $expect
     * @param  string $assert
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideInsurancePossibilitiesData
     */
    public function testInsurancePossibilities(array $data, array $expect, string $assert): void
    {
        $consignment = $this->getConsignment($data);

        self::assertEquals(
            $expect,
            $consignment->getInsurancePossibilities($assert),
            "Wrong insurance possibilities for $assert"
        );
    }

    /**
     * @param  array  $data
     * @param  bool   $expect
     * @param  string $assert
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideCanHavePackageTypeData
     */
    public function testCanHavePackageType(array $data, bool $expect, string $assert): void
    {
        $consignment = $this->getConsignment($data);

        self::assertEquals(
            $expect,
            $consignment->canHavePackageType($assert),
            "Wrong package type $assert"
        );
    }

    /**
     * @param  array  $data
     * @param  bool   $expect
     * @param  string $assert
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideCanHaveDeliveryTypeData
     */
    public function testCanHaveDeliveryType(array $data, bool $expect, string $assert): void
    {
        $consignment = $this->getConsignment($data);

        self::assertEquals(
            $expect,
            $consignment->canHaveDeliveryType($assert),
            "Wrong delivery type $assert"
        );
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function getConsignment(array $data): AbstractConsignment
    {
        $collection = $this->generateCollection($data);
        self::assertCount(1, $collection, 'Collection expected to have only one result.');

        return $collection->first();
    }
}
