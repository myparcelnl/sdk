<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\CoreApi;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * RefTypesCarrierV2 disappeared from the OpenAPI spec, but published v11 consumers still use it.
 *
 * Keep this compatibility contract for the lifetime of v11. The class and this test can be
 * removed together in v12.
 */
final class RefTypesCarrierV2CompatibilityTest extends TestCase
{
    /**
     * @var array<string, string>
     */
    private const V11_CONSTANTS = [
        'POSTNL'              => 'POSTNL',
        'BPOST'               => 'BPOST',
        'CHEAP_CARGO'         => 'CHEAP_CARGO',
        'DPD'                 => 'DPD',
        'BOL'                 => 'BOL',
        'DHL_FOR_YOU'         => 'DHL_FOR_YOU',
        'DHL_PARCEL_CONNECT'  => 'DHL_PARCEL_CONNECT',
        'DHL_EUROPLUS'        => 'DHL_EUROPLUS',
        'UPS_STANDARD'        => 'UPS_STANDARD',
        'UPS_EXPRESS_SAVER'   => 'UPS_EXPRESS_SAVER',
        'GLS'                 => 'GLS',
        'BRT'                 => 'BRT',
        'TRUNKRS'             => 'TRUNKRS',
        'INPOST'              => 'INPOST',
        'POSTE_ITALIANE'      => 'POSTE_ITALIANE',
    ];

    public function testLegacyCarrierEnumRemainsAvailableThroughoutV11(): void
    {
        self::assertTrue(class_exists(RefTypesCarrierV2::class));

        foreach (self::V11_CONSTANTS as $name => $value) {
            $qualifiedConstant = RefTypesCarrierV2::class . '::' . $name;

            self::assertTrue(
                defined($qualifiedConstant),
                sprintf('%s must remain available throughout v11.', $name)
            );
            self::assertSame($value, constant($qualifiedConstant));
            self::assertContains($value, RefTypesCarrierV2::getAllowableEnumValues());
        }
    }
}
