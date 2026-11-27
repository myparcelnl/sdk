<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Capabilities;

use MyParcelNL\Sdk\Client\CoreApiModels;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesResponseCapabilityV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;
use MyParcelNL\Sdk\Services\Capabilities\SupportedCapabilitiesFilter;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

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
        $this->assertSame('POSTNL', $filtered[0]->getCarrier());
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

        $this->assertSame(['PACKAGE'], $filtered[0]->getPackageTypes());
        $this->assertSame(['STANDARD_DELIVERY'], $filtered[0]->getDeliveryTypes());
    }

    /**
     * @dataProvider provideTestFilterData
     *
     * @param  class-string $class
     *
     * @return void
     */
    public function testFilterKeepsOptionsWithAV1Name(string $class): void
    {
        $options = SupportedCapabilitiesFilter::filter($this->response($class))[0]->getOptions();

        $this->assertNotNull($options->getRequiresSignature());
        $this->assertNotNull($options->getTracked());
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
        $before = json_encode(array_map([CoreApiModels::class, 'toArray'], $models));

        SupportedCapabilitiesFilter::filter($models);

        $this->assertSame($before, json_encode(array_map([CoreApiModels::class, 'toArray'], $models)));
    }

    /**
     * Every option of the current models has a v1 name, so a model with an extra option stands in for an
     * option that a newer spec adds.
     *
     * @return void
     */
    public function testFilterRemovesAnOptionWithoutAV1Name(): void
    {
        $options = new class extends RefCapabilitiesResponseOptionsOptionsV2 {
            public static function openAPITypes()
            {
                return parent::openAPITypes() + ['foo_option' => RefCapabilitiesResponseOptionsOptionV2::class];
            }
        };
        $options->setRequiresSignature(new RefCapabilitiesResponseOptionsOptionV2());
        $options->offsetSet('foo_option', new RefCapabilitiesResponseOptionsOptionV2());

        $model = $this->response(RefCapabilitiesResponseCapabilityV2::class)[0];
        $model->setOptions($options);

        $filtered = SupportedCapabilitiesFilter::filter([$model])[0]->getOptions();

        $this->assertNull($filtered->offsetGet('foo_option'));
        $this->assertNotNull($filtered->getRequiresSignature());
        $this->assertNotNull($options->offsetGet('foo_option'));
    }

    /**
     * @return void
     */
    public function testAnOptionKeyThatTheModelDoesNotHaveIsNotInTheResponse(): void
    {
        $model = ObjectSerializer::deserialize(
            (object) ['carrier' => 'POSTNL', 'options' => ['fooOption' => ['isRequired' => true]]],
            RefCapabilitiesResponseCapabilityV2::class
        );

        $this->assertArrayNotHasKey('fooOption', CoreApiModels::toArray(SupportedCapabilitiesFilter::filter([$model])[0]->getOptions()));
    }

    /**
     * @dataProvider provideTestSingleValueChecksData
     *
     * @param  string $check
     * @param  string $value
     * @param  bool   $expected
     *
     * @return void
     */
    public function testSingleValueChecks(string $check, string $value, bool $expected): void
    {
        $this->assertSame($expected, SupportedCapabilitiesFilter::$check($value));
    }

    /**
     * @return array<string, array{string, string, bool}>
     */
    public function provideTestSingleValueChecksData(): array
    {
        return [
            'known carrier'          => ['isCarrierSupported', 'POSTNL', true],
            'unknown carrier'        => ['isCarrierSupported', 'FOO_CARRIER', false],
            'delivery options name'  => ['isCarrierSupported', 'postnl', false],
            'known delivery type'    => ['isDeliveryTypeSupported', 'PICKUP_DELIVERY', true],
            'unknown delivery type'  => ['isDeliveryTypeSupported', 'FOO_DELIVERY', false],
            'known package type'     => ['isPackageTypeSupported', 'SMALL_PACKAGE', true],
            'unknown package type'   => ['isPackageTypeSupported', 'FOO_PACKAGE', false],
            'known option'           => ['isOptionSupported', 'requires_signature', true],
            'known option JSON name' => ['isOptionSupported', 'requiresSignature', true],
            'unknown option'         => ['isOptionSupported', 'foo_option', false],
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
        return ObjectSerializer::deserialize(json_decode(json_encode(self::RESPONSE)), $class . '[]');
    }
}
