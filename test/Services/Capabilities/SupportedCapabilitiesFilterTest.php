<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\Capabilities\SupportedCapabilitiesFilter;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

final class SupportedCapabilitiesFilterTest extends TestCase
{
    private const RESPONSE = [
        [
            'carrier'       => 'POSTNL',
            'packageTypes'  => ['PACKAGE', 'FOO_PACKAGE'],
            'deliveryTypes' => ['STANDARD_DELIVERY', 'FOO_DELIVERY'],
            'options'       => [
                'requiresSignature' => ['isRequired' => false],
                'tracked'           => ['isRequired' => false],
                'fooOption'         => ['isRequired' => false],
            ],
        ],
        [
            'carrier'       => 'FOO_CARRIER',
            'packageTypes'  => ['PACKAGE'],
            'deliveryTypes' => ['STANDARD_DELIVERY'],
        ],
    ];

    /**
     * @dataProvider provideTestFilterData
     *
     * @param  class-string $class
     *
     * @return void
     */
    public function testFilterDropsACarrierWithoutAV1Id(string $class): void
    {
        $filtered = SupportedCapabilitiesFilter::filter($this->response($class));

        $this->assertCount(1, $filtered);
        $this->assertSame(RefCapabilitiesSharedCarrierV2::Postnl, $filtered[0]->carrier);
    }

    /**
     * @dataProvider provideTestFilterData
     *
     * @param  class-string $class
     *
     * @return void
     */
    public function testFilterRemovesTypesWithoutAV1IdOrDeliveryOptionsName(string $class): void
    {
        $filtered = SupportedCapabilitiesFilter::filter($this->response($class));

        $this->assertSame([RefShipmentPackageTypeV2::Package], $filtered[0]->packageTypes);
        $this->assertSame([RefTypesDeliveryTypeV2::Standard], $filtered[0]->deliveryTypes);
    }

    /**
     * @dataProvider provideTestFilterData
     *
     * @param  class-string $class
     *
     * @return void
     */
    public function testFilterKeepsOptionsWithAV1NameAndRemovesAnUnknownOption(string $class): void
    {
        $options = CoreApiModels::toArray(SupportedCapabilitiesFilter::filter($this->response($class))[0]->options);

        $this->assertArrayHasKey('requiresSignature', $options);
        $this->assertArrayHasKey('tracked', $options);
        $this->assertArrayNotHasKey('fooOption', $options);
    }

    /**
     * @dataProvider provideTestFilterData
     *
     * @param  class-string $class
     *
     * @return void
     */
    public function testFilterLeavesTheInputUnchanged(string $class): void
    {
        $models = $this->response($class);
        $before = array_map([CoreApiModels::class, 'toArray'], $models);

        SupportedCapabilitiesFilter::filter($models);

        $this->assertSame($before, array_map([CoreApiModels::class, 'toArray'], $models));
    }

    /**
     * Every defined option of the current models has a v1 name, so a model with an extra defined option
     * stands in for an option that a newer spec adds.
     *
     * @return void
     */
    public function testFilterRemovesADefinedOptionWithoutAV1Name(): void
    {
        $options = new class extends RefCapabilitiesResponseOptionsOptionsV2 {
            public RefCapabilitiesResponseOptionsOptionV2 $fooOption;

            public function definedProperties(): array
            {
                return parent::definedProperties() + ['fooOption' => 'fooOption'];
            }
        };
        $options->requiresSignature = new RefCapabilitiesResponseOptionsOptionV2();
        $options->fooOption         = new RefCapabilitiesResponseOptionsOptionV2();

        $model          = $this->response(RefCapabilitiesResponseCapabilityV2::class)[0];
        $model->options = $options;

        $filtered = SupportedCapabilitiesFilter::filter([$model])[0]->options;

        $this->assertFalse(isset($filtered->fooOption));
        $this->assertTrue(isset($filtered->requiresSignature));
        $this->assertTrue(isset($options->fooOption));
    }

    /**
     * @dataProvider provideTestSingleValueChecksData
     *
     * @param  string                                                          $check
     * @param  RefCapabilitiesSharedCarrierV2|RefShipmentPackageTypeV2|RefTypesDeliveryTypeV2|string $value
     * @param  bool                                                            $expected
     *
     * @return void
     */
    public function testSingleValueChecks(string $check, $value, bool $expected): void
    {
        $this->assertSame($expected, SupportedCapabilitiesFilter::$check($value));
    }

    /**
     * @return array<string, array{string, mixed, bool}>
     */
    public function provideTestSingleValueChecksData(): array
    {
        return [
            'carrier enum'           => ['isCarrierSupported', RefCapabilitiesSharedCarrierV2::Postnl, true],
            'carrier string'         => ['isCarrierSupported', 'POSTNL', true],
            'unknown carrier'        => ['isCarrierSupported', 'FOO_CARRIER', false],
            'delivery options name'  => ['isCarrierSupported', 'postnl', false],
            'delivery type enum'     => ['isDeliveryTypeSupported', RefTypesDeliveryTypeV2::Pickup, true],
            'unknown delivery type'  => ['isDeliveryTypeSupported', 'FOO_DELIVERY', false],
            'package type enum'      => ['isPackageTypeSupported', RefShipmentPackageTypeV2::SmallPackage, true],
            'unknown package type'   => ['isPackageTypeSupported', 'FOO_PACKAGE', false],
            'known option'           => ['isOptionSupported', 'requiresSignature', true],
            'unknown option'         => ['isOptionSupported', 'fooOption', false],
        ];
    }

    /**
     * @return array<string, array{class-string}>
     */
    public function provideTestFilterData(): array
    {
        return [
            'capabilities'         => [RefCapabilitiesResponseCapabilityV2::class],
            'contract definitions' => [RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2::class],
        ];
    }

    /**
     * @param  class-string $class
     *
     * @return array<int, object>
     */
    private function response(string $class): array
    {
        return (new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]))
            ->denormalize(self::RESPONSE, $class . '[]', 'json');
    }
}
