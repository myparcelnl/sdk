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
                'cc'     => AbstractConsignment::CC_NL,
                'expect' => [100, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000],
            ],
            'NL -> BE' => [
                'data'   => self::BASE_DATA,
                'cc'     => AbstractConsignment::CC_BE,
                'expect' => [500],
            ],
            'NL -> FR' => [
                'data'   => self::BASE_DATA,
                'cc'     => self::CC_FR,
                'expect' => [50, 500],
            ],
        ];
    }

    public function provideCanHavePackageTypeData(): array
    {
        return [
            'NL' => [
                'data'        => self::BASE_DATA,
                'packageType' => AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME,
                'expect'      => true,
            ],
        ];
    }

    public function provideCanHaveDeliveryTypeData(): array
    {
        return [
            'Standard' => [
                'data'         => self::BASE_DATA,
                'deliveryType' => AbstractConsignment::DELIVERY_TYPE_STANDARD_NAME,
                'expect'       => true,
            ],
            'Evening'  => [
                'data'         => self::BASE_DATA + ['packageType' => AbstractConsignment::PACKAGE_TYPE_PACKAGE],
                'deliveryType' => AbstractConsignment::DELIVERY_TYPE_EVENING_NAME,
                'expect'       => true,
            ],
        ];
    }

    /**
     * @param  array  $data
     * @param  array  $expect
     * @param  string $cc
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideInsurancePossibilitiesData
     */
    public function testInsurancePossibilities(array $data, string $cc, array $expect): void
    {
        $consignment = $this->getConsignment($data);

        self::assertEquals(
            $expect,
            $consignment->getInsurancePossibilities($cc),
            "Wrong insurance possibilities for $cc"
        );
    }

    /**
     * @param  array  $data
     * @param  bool   $expect
     * @param  string $packageType
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideCanHavePackageTypeData
     */
    public function testCanHavePackageType(array $data, string $packageType, bool $expect): void
    {
        $consignment = $this->getConsignment($data);

        self::assertEquals(
            $expect,
            $consignment->canHavePackageType($packageType),
            "Wrong package type $packageType"
        );
    }

    /**
     * @param  array  $data
     * @param  bool   $expect
     * @param  string $deliveryType
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideCanHaveDeliveryTypeData
     */
    public function testCanHaveDeliveryType(array $data, string $deliveryType, bool $expect): void
    {
        $consignment = $this->getConsignment($data);

        self::assertEquals(
            $expect,
            $consignment->canHaveDeliveryType($deliveryType),
            "Wrong delivery type $deliveryType"
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
