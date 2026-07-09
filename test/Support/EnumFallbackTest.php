<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Support;

use MyParcelNL\Sdk\Support\EnumFallback;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class EnumFallbackTest extends TestCase
{
    protected function tearDown(): void
    {
        EnumFallback::setListener(null);
        parent::tearDown();
    }

    public function testReturnsTheRawValueUnchanged(): void
    {
        self::assertSame('NEW_CARRIER', EnumFallback::onUnknown('SomeEnum', 'NEW_CARRIER'));
    }

    public function testWorksWithoutAListener(): void
    {
        self::assertSame(42, EnumFallback::onUnknown('SomeEnum', 42));
    }

    public function testNotifiesTheListenerWithEnumClassAndValue(): void
    {
        $seen = [];
        EnumFallback::setListener(static function (string $enumClass, $value) use (&$seen): void {
            $seen[] = [$enumClass, $value];
        });

        EnumFallback::onUnknown('SomeEnum', 'NEW_CARRIER');

        self::assertSame([['SomeEnum', 'NEW_CARRIER']], $seen);
    }
}
