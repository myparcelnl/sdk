<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Account;

use MyParcelNL\Sdk\Model\Account\GeneralSettings;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class GeneralSettingsTest extends TestCase
{
    public function testGeneralSettingsGetters(): void
    {
        $settings = new GeneralSettings([
            'allow_printerless_return'           => true,
            'has_carrier_cbs_contract'           => false,
            'has_carrier_contract'               => true,
            'has_carrier_small_package_contract' => false,
            'is_test'                            => true,
            'my_returns'                         => 'active',
            'order_mode'                         => false,
            'postnl_mailbox_international'       => true,
        ]);

        self::assertTrue($settings->allowPrinterlessReturn());
        self::assertFalse($settings->hasCarrierCbsContract());
        self::assertTrue($settings->hasCarrierContract());
        self::assertFalse($settings->hasCarrierSmallPackageContract());
        self::assertTrue($settings->isTest());
        self::assertTrue($settings->hasMyReturns());
        self::assertFalse($settings->isOrderMode());
        self::assertTrue($settings->hasPostnlMailboxInternational());
    }

    public function testGeneralSettingsToArray(): void
    {
        $settings = new GeneralSettings([
            'allow_printerless_return'           => false,
            'has_carrier_cbs_contract'           => true,
            'has_carrier_contract'               => false,
            'has_carrier_small_package_contract' => true,
            'is_test'                            => false,
            'my_returns'                         => 'inactive',
            'order_mode'                         => true,
            'postnl_mailbox_international'       => false,
        ]);

        $expected = [
            'allow_printerless_return'           => false,
            'has_carrier_cbs_contract'           => true,
            'has_carrier_contract'               => false,
            'has_carrier_small_package_contract' => true,
            'is_test'                            => false,
            'my_returns'                         => false,
            'order_mode'                         => true,
            'postnl_mailbox_international'       => false,
        ];

        self::assertEquals($expected, $settings->toArray());
    }
}
