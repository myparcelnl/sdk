<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Account;

use MyParcelNL\Sdk\Model\Account\Account;
use MyParcelNL\Sdk\Model\Account\GeneralSettings;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AccountTest extends TestCase
{
    public function testAccountContainsGeneralSettings(): void
    {
        $mockGeneralSettings = [
            'allow_printerless_return' => true,
            'is_test'                  => false,
        ];

        $mockData = [
            'id'               => 123,
            'proposition_id'   => 456,
            'shops'            => [],
            'general_settings' => $mockGeneralSettings,
        ];

        $account = new Account($mockData);

        self::assertInstanceOf(GeneralSettings::class, $account->getGeneralSettings());
        self::assertTrue($account->getGeneralSettings()->allowPrinterlessReturn());
        self::assertFalse($account->getGeneralSettings()->isTest());
    }
}
