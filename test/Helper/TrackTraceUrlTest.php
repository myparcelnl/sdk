<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\Helper\TrackTraceUrl;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class TrackTraceUrlTest extends TestCase
{
    /**
     * @return array
     */
    public function provideTrackTraceData(): array
    {
        return [
            'dutch destination' => [
                'barcode'     => '3SMYPA00123456',
                'postal_code' => '2132JE',
                'cc'          => 'NL',
            ],
        ];
    }

    /**
     * @dataProvider provideTrackTraceData()
     *
     * @param  string $barcode
     * @param  string $postalCode
     * @param  string $countryCode
     */
    public function testCreate(string $barcode, string $postalCode, string $countryCode): void
    {
        $trackTraceUrl = TrackTraceUrl::create($barcode, $postalCode, $countryCode);

        self::assertSame(
            "https://myparcel.me/track-trace/$barcode/$postalCode/$countryCode",
            $trackTraceUrl
        );
    }
}
