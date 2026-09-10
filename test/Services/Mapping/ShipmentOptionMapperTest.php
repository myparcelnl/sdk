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
}
