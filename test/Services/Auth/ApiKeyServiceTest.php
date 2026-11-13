<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Auth;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ServerException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\RoleShop;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\RoleUser;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Auth\ApiKeyService;
use MyParcelNL\Sdk\Test\Bootstrap\MocksHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use UnexpectedValueException;

final class ApiKeyServiceTest extends TestCase
{
    use MocksHttpClient;

    private const SHOP_PRINCIPAL = [
        'accountId' => '225196',
        'platform'  => 'MYPARCEL_NL',
        'id'        => '156402',
        'mfaEnabled' => false,
        'role'      => 'SHOP_DEFAULT',
        'shopIds'   => ['156402'],
        'type'      => 'SHOP',
    ];

    private const USER_PRINCIPAL = [
        'accountId' => '225196',
        'platform'  => 'MYPARCEL_NL',
        'id'        => '156403',
        'mfaEnabled' => false,
        'role'      => 'CUSTOMER_MAIN',
        'shopIds'   => ['156402', '156404'],
        'type'      => 'USER',
    ];

    public function testGetPrincipalAsksIamWithTheApiKey(): void
    {
        $this->createService(self::SHOP_PRINCIPAL)->getPrincipal();

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://iam.api.myparcel.nl/whoami', $this->requests[0]['url']);
        self::assertSame('Bearer ' . base64_encode('plain_api_key'), $this->header('authorization'));
    }

    public function testGetPrincipalReturnsAShopPrincipal(): void
    {
        $principal = $this->createService(self::SHOP_PRINCIPAL)->getPrincipal();

        self::assertInstanceOf(PrincipalShop::class, $principal);
        self::assertSame(RoleShop::ShopDefault, $principal->role);
        self::assertSame(['156402'], $principal->shopIds);
    }

    public function testGetPrincipalReturnsAUserPrincipal(): void
    {
        $principal = $this->createService(self::USER_PRINCIPAL)->getPrincipal();

        self::assertInstanceOf(PrincipalUser::class, $principal);
        self::assertSame(RoleUser::CustomerMain, $principal->role);
        self::assertSame(['156402', '156404'], $principal->shopIds);
    }

    public function testGetPrincipalKeepsARoleAndAPlatformThatTheSdkDoesNotKnow(): void
    {
        $principal = $this->createService(['role' => 'SHOP_NEW', 'platform' => 'MYPARCEL_DE'] + self::SHOP_PRINCIPAL)->getPrincipal();

        self::assertSame('SHOP_NEW', $principal->role);
        self::assertSame('MYPARCEL_DE', $principal->platform);
    }

    public function testGetPrincipalThrowsForAnUnknownType(): void
    {
        $service = $this->createService(['type' => 'SERVICE'] + self::SHOP_PRINCIPAL);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('IAM returned an unknown principal type: "SERVICE"');

        $service->getPrincipal();
    }

    /**
     * A 200 proves the key. Only `getPrincipal()` needs a type that the SDK knows.
     */
    public function testIsValidReturnsTrueForAPrincipalTypeThatTheSdkDoesNotKnow(): void
    {
        self::assertTrue($this->createService(['type' => 'SERVICE'] + self::SHOP_PRINCIPAL)->isValid());
    }

    public function testIsValidReturnsTrueWhenWhoamiSucceeds(): void
    {
        self::assertTrue($this->createService(self::SHOP_PRINCIPAL)->isValid());
    }

    public function testIsValidReturnsFalseForUnauthorizedResponse(): void
    {
        self::assertFalse($this->createService(['message' => 'Unauthorized'], 401)->isValid());
    }

    public function testIsValidThrowsForAServerError(): void
    {
        $service = $this->createService(['title' => 'Server error'], 500, 'application/problem+json');

        $this->expectException(ServerException::class);

        $service->isValid();
    }

    /**
     * The generated endpoint reads a 200 only with a JSON content type. Another 200 is not an answer
     * of IAM, so it does not prove the key.
     */
    public function testIsValidThrowsForA200WithoutAJsonBody(): void
    {
        $service = $this->createService(self::SHOP_PRINCIPAL, 200, 'text/plain');

        $this->expectException(ClientException::class);

        $service->isValid();
    }

    public function testIsValidThrowsForAnErrorStatusThatTheSpecDoesNotDeclare(): void
    {
        $service = $this->createService(['message' => 'Forbidden'], 403);

        $this->expectException(ClientException::class);

        $service->isValid();
    }

    /**
     * @param array<string, mixed> $response
     */
    private function createService(array $response, int $status = 200, string $contentType = 'application/json'): ApiKeyService
    {
        $httpClient = $this->mockHttpClient(json_encode($response, JSON_THROW_ON_ERROR), $status, $contentType);

        return new ApiKeyService('plain_api_key', JaneClientFactory::iamApi('plain_api_key', null, null, [], $httpClient));
    }
}
