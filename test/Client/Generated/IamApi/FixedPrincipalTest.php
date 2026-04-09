<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\IamApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\FixedPrincipal;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\Principal;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class FixedPrincipalTest extends TestCase
{
    public function testShopPrincipalAcceptsShopRoleAndSingleShopId(): void
    {
        $principal = new FixedPrincipal([
            'account_id' => '225196',
            'platform' => 'MYPARCEL_NL',
            'id' => '156402',
            'role' => 'SHOP_DEFAULT',
            'shop_ids' => ['156402'],
            'type' => 'SHOP',
        ]);

        self::assertInstanceOf(FixedPrincipal::class, $principal);
        self::assertInstanceOf(Principal::class, $principal);
        self::assertSame('SHOP', $principal->getType());
        self::assertSame('SHOP_DEFAULT', $principal->getRole());
        self::assertSame(['156402'], $principal->getShopIds());
        self::assertTrue($principal->valid());
    }

    public function testUserPrincipalAcceptsUserRoleAndMultipleShopIds(): void
    {
        $principal = new FixedPrincipal([
            'account_id' => '225196',
            'platform' => 'MYPARCEL_NL',
            'id' => '156403',
            'role' => 'CUSTOMER_MAIN',
            'shop_ids' => ['156402', '156403'],
            'type' => 'USER',
        ]);

        self::assertSame('USER', $principal->getType());
        self::assertSame('CUSTOMER_MAIN', $principal->getRole());
        self::assertSame(['156402', '156403'], $principal->getShopIds());
        self::assertTrue($principal->valid());
    }

    public function testShopPrincipalRejectsUserRole(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 'CUSTOMER_MAIN' for 'role' when type is 'SHOP'");

        new FixedPrincipal([
            'account_id' => '225196',
            'platform' => 'MYPARCEL_NL',
            'id' => '156402',
            'role' => 'CUSTOMER_MAIN',
            'shop_ids' => ['156402'],
            'type' => 'SHOP',
        ]);
    }

    public function testShopPrincipalRejectsMultipleShopIds(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value for 'shop_ids' when type is 'SHOP'");

        new FixedPrincipal([
            'account_id' => '225196',
            'platform' => 'MYPARCEL_NL',
            'id' => '156402',
            'role' => 'SHOP_DEFAULT',
            'shop_ids' => ['156402', '156403'],
            'type' => 'SHOP',
        ]);
    }
}
