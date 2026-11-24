<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2;
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
            'legacy name'        => ['signature', 'requiresSignature'],
            'v2 JSON name'       => ['requiresSignature', 'requiresSignature'],
            'v11 property name'  => ['requires_signature', 'requiresSignature'],
            'shared name'        => ['same_day_delivery', 'sameDayDelivery'],
            'case variant'       => ['SAME_DAY_DELIVERY', 'sameDayDelivery'],
            'spaces'             => ['same day delivery', 'sameDayDelivery'],
            'no tracking'        => ['noTracking', 'noTracking'],
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
            'v2 property'     => ['requiresSignature', 'signature'],
            'snake_case name' => ['requires_signature', 'signature'],
            'shared name'     => ['sameDayDelivery', 'same_day_delivery'],
            'returns option'  => ['printReturnLabelAtDropOff', 'printerless_return'],
            'tracked'         => ['tracked', 'tracked'],
            'unknown option'  => ['unknownOption', null],
            'empty name'      => ['', null],
        ];
    }

    /**
     * @return void
     */
    public function testEveryCapabilitiesResponseOptionHasAV1Name(): void
    {
        $mapper = new ShipmentOptionMapper();

        foreach ([new RefCapabilitiesResponseOptionsOptionsV2(), new RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2()] as $options) {
            foreach (array_keys($options->definedProperties()) as $property) {
                $this->assertNotNull($mapper->v1NameFromV2Property($property), get_class($options) . "::$property");
            }
        }
    }
}
