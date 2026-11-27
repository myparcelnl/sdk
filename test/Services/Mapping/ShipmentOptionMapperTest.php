<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentOptionMapperTest extends TestCase
{
    /**
     * @dataProvider provideTestV2PropertyFromNameData
     *
     * @param  string      $name
     * @param  null|string $expected
     *
     * @return void
     */
    public function testV2PropertyFromName(string $name, ?string $expected): void
    {
        $this->assertSame($expected, (new ShipmentOptionMapper())->v2PropertyFromName($name));
    }

    /**
     * @return array<string, array{string, null|string}>
     */
    public function provideTestV2PropertyFromNameData(): array
    {
        return [
            'legacy name'        => ['signature', 'requires_signature'],
            'v2 JSON name'       => ['requiresSignature', 'requires_signature'],
            'v2 PHP property'    => ['requires_signature', 'requires_signature'],
            'shared name'        => ['same_day_delivery', 'same_day_delivery'],
            'case variant'       => ['SAME_DAY_DELIVERY', 'same_day_delivery'],
            'spaces'             => ['same day delivery', 'same_day_delivery'],
            'no tracking'        => ['noTracking', 'no_tracking'],
            'unsupported option' => ['tracked', null],
            'unknown option'     => ['unknown_option', null],
            'empty name'         => ['', null],
        ];
    }

    /**
     * @dataProvider provideTestV1NameFromV2PropertyData
     *
     * @param  string      $property
     * @param  null|string $expected
     *
     * @return void
     */
    public function testV1NameFromV2Property(string $property, ?string $expected): void
    {
        $this->assertSame($expected, (new ShipmentOptionMapper())->v1NameFromV2Property($property));
    }

    /**
     * @return array<string, array{string, null|string}>
     */
    public function provideTestV1NameFromV2PropertyData(): array
    {
        return [
            'v2 PHP property' => ['requires_signature', 'signature'],
            'v2 JSON name'    => ['requiresSignature', 'signature'],
            'shared name'     => ['same_day_delivery', 'same_day_delivery'],
            'returns option'  => ['print_return_label_at_drop_off', 'printerless_return'],
            'tracked'         => ['tracked', 'tracked'],
            'unknown option'  => ['unknown_option', null],
            'empty name'      => ['', null],
        ];
    }

    /**
     * @return void
     */
    public function testEveryCapabilitiesResponseOptionHasAV1Name(): void
    {
        $mapper = new ShipmentOptionMapper();
        $models = [RefCapabilitiesResponseOptionsOptionsV2::class, RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2::class];

        foreach ($models as $model) {
            foreach (array_keys($model::openAPITypes()) as $property) {
                $this->assertNotNull($mapper->v1NameFromV2Property($property), "$model::$property");
            }
        }
    }
}
