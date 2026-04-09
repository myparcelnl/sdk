<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Generated\IamApi;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use MyParcelNL\Sdk\Client\Generated\IamApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\IamApi\Configuration;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\FixedPrincipal;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\Principal;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class DefaultApiWhoamiTest extends TestCase
{
    public function testWhoamiGetDeserializesShopResponseAsFixedPrincipal(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())
            ->method('send')
            ->with(
                self::callback(function (RequestInterface $request): bool {
                    self::assertSame('GET', $request->getMethod());
                    self::assertSame('/whoami', $request->getUri()->getPath());
                    self::assertSame('Bearer encoded_api_key', $request->getHeaderLine('Authorization'));

                    return true;
                }),
                self::isType('array')
            )
            ->willReturn(new Response(200, [], json_encode([
                'accountId' => '225196',
                'platform' => 'MYPARCEL_NL',
                'id' => '156402',
                'role' => 'SHOP_DEFAULT',
                'shopIds' => ['156402'],
                'type' => 'SHOP',
            ], JSON_THROW_ON_ERROR)));

        $config = new Configuration();
        $config->setHost('https://iam.api.myparcel.nl');
        $config->setAccessToken('encoded_api_key');

        $api = new DefaultApi($client, $config);
        $principal = $api->whoamiGet();

        self::assertInstanceOf(FixedPrincipal::class, $principal);
        self::assertInstanceOf(Principal::class, $principal);
        self::assertSame('SHOP', $principal->getType());
        self::assertSame('SHOP_DEFAULT', $principal->getRole());
        self::assertSame(['156402'], $principal->getShopIds());
        self::assertTrue($principal->valid());
    }

    public function testWhoamiGetDeserializesUserResponseAsFixedPrincipal(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())
            ->method('send')
            ->willReturn(new Response(200, [], json_encode([
                'accountId' => '225196',
                'platform' => 'MYPARCEL_NL',
                'id' => '156403',
                'role' => 'CUSTOMER_MAIN',
                'shopIds' => ['156402', '156403'],
                'type' => 'USER',
            ], JSON_THROW_ON_ERROR)));

        $config = new Configuration();
        $config->setHost('https://iam.api.myparcel.nl');
        $config->setAccessToken('encoded_api_key');

        $api = new DefaultApi($client, $config);
        $principal = $api->whoamiGet();

        self::assertInstanceOf(FixedPrincipal::class, $principal);
        self::assertSame('USER', $principal->getType());
        self::assertSame('CUSTOMER_MAIN', $principal->getRole());
        self::assertSame(['156402', '156403'], $principal->getShopIds());
        self::assertTrue($principal->valid());
    }
}
