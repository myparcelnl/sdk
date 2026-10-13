<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Auth;

use MyParcelNL\Sdk\Client\Generated\IamApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\IamApi\ApiException;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\FixedPrincipal;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\Principal;
use MyParcelNL\Sdk\Services\Auth\ApiKeyService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ApiKeyServiceTest extends TestCase
{
    public function testGetPrincipalReturnsGeneratedWhoamiPrincipal(): void
    {
        $principal = $this->createShopPrincipal();

        $api = $this->createMock(DefaultApi::class);
        $api->expects(self::once())
            ->method('whoamiGet')
            ->willReturn($principal);

        $service = new ApiKeyService('plain_api_key', $api);

        self::assertSame($principal, $service->getPrincipal());
    }

    public function testIsValidReturnsTrueWhenWhoamiSucceeds(): void
    {
        $api = $this->createMock(DefaultApi::class);
        $api->expects(self::once())
            ->method('whoamiGet')
            ->willReturn($this->createShopPrincipal());

        $service = new ApiKeyService('plain_api_key', $api);

        self::assertTrue($service->isValid());
    }

    public function testIsValidReturnsFalseForUnauthorizedResponse(): void
    {
        $api = $this->createMock(DefaultApi::class);
        $api->expects(self::once())
            ->method('whoamiGet')
            ->willThrowException(new ApiException('Unauthorized', 401));

        $service = new ApiKeyService('plain_api_key', $api);

        self::assertFalse($service->isValid());
    }

    public function testIsValidRethrowsNonAuthorizationErrors(): void
    {
        $api = $this->createMock(DefaultApi::class);
        $api->expects(self::once())
            ->method('whoamiGet')
            ->willThrowException(new ApiException('Server error', 500));

        $service = new ApiKeyService('plain_api_key', $api);

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(500);

        $service->isValid();
    }

    private function createShopPrincipal(): Principal
    {
        return new FixedPrincipal([
            'account_id' => '225196',
            'platform' => 'MYPARCEL_NL',
            'id' => '156402',
            'role' => 'SHOP_DEFAULT',
            'shop_ids' => ['156402'],
            'type' => 'SHOP',
        ]);
    }
}
