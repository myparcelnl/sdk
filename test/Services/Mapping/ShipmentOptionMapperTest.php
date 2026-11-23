<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping;

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
}
