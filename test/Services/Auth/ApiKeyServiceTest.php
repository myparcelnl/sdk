<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Auth;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\RoleShop;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\RoleUser;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Auth\ApiKeyService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use UnexpectedValueException;

final class ApiKeyServiceTest extends TestCase
{
    private const SHOP_PRINCIPAL = [
        'accountId' => '225196',
        'platform'  => 'MYPARCEL_NL',
        'id'        => '156402',
        'role'      => 'SHOP_DEFAULT',
        'shopIds'   => ['156402'],
        'type'      => 'SHOP',
    ];

    private const USER_PRINCIPAL = [
        'accountId' => '225196',
        'platform'  => 'MYPARCEL_NL',
        'id'        => '156403',
        'role'      => 'CUSTOMER_MAIN',
        'shopIds'   => ['156402', '156404'],
        'type'      => 'USER',
    ];

    /**
     * @var array<int, array{method: string, url: string, options: array<string, mixed>}>
     */
    private array $requests = [];

    public function testGetPrincipalAsksIamWithTheApiKey(): void
    {
        $this->createService(self::SHOP_PRINCIPAL)->getPrincipal();

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://iam.api.myparcel.nl/whoami', $this->requests[0]['url']);
        self::assertSame(
            'Authorization: Bearer ' . base64_encode('plain_api_key'),
            $this->requests[0]['options']['normalized_headers']['authorization'][0]
        );
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

    public function testGetPrincipalThrowsForAnUnknownType(): void
    {
        $service = $this->createService(['type' => 'SERVICE'] + self::SHOP_PRINCIPAL);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('IAM returned an unknown principal type: "SERVICE"');

        $service->getPrincipal();
    }

    public function testIsValidReturnsTrueWhenWhoamiSucceeds(): void
    {
        self::assertTrue($this->createService(self::SHOP_PRINCIPAL)->isValid());
    }

    public function testIsValidReturnsFalseForUnauthorizedResponse(): void
    {
        self::assertFalse($this->createService(['message' => 'Unauthorized'], 401)->isValid());
    }

    public function testIsValidRethrowsNonAuthorizationErrors(): void
    {
        $service = $this->createService(['message' => 'Server error'], 500);

        $this->expectException(ClientException::class);

        $service->isValid();
    }

    /**
     * @param array<string, mixed> $response
     */
    private function createService(array $response, int $status = 200): ApiKeyService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($response, $status): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'options' => $options];

            return new MockResponse(
                json_encode($response, JSON_THROW_ON_ERROR),
                ['http_code' => $status, 'response_headers' => ['content-type' => 'application/json']]
            );
        });

        return new ApiKeyService('plain_api_key', JaneClientFactory::iamApi('plain_api_key', null, null, [], $httpClient));
    }
}
