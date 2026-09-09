<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Ecommerce;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Order;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Services\Ecommerce\EcommerceApiFactory;
use MyParcelNL\Sdk\Test\Services\Connect\ConnectServiceTestCase;
use ReflectionProperty;

final class EcommerceApiFactoryTest extends ConnectServiceTestCase
{
    public function testTheHostComesFromTheConnectConfig(): void
    {
        $api = EcommerceApiFactory::make($this->service());

        self::assertInstanceOf(DefaultApi::class, $api);
        self::assertSame(self::HOST, $api->getConfig()->getHost(), 'not the generated http://localhost');
    }

    public function testAHostArgumentOverridesItForResourceCallsOnly(): void
    {
        $connect = $this->service();
        $api     = EcommerceApiFactory::make($connect, 'https://tunnel.example.test');

        self::assertSame('https://tunnel.example.test', $api->getConfig()->getHost());
        self::assertSame(
            self::HOST,
            $connect->getConfig()->getHost(),
            'the connect calls keep going to the host ConnectConfig builds'
        );
    }

    public function testAnEmptyHostReadsAsNotProvided(): void
    {
        // A consumer's unset setting arrives as '' rather than null. Taking it would resolve every
        // URL against nothing. The other API factories read it the same way.
        $api = EcommerceApiFactory::make($this->service(), '');

        self::assertSame(self::HOST, $api->getConfig()->getHost());
    }

    public function testAnEmptyUserAgentReadsAsNotProvided(): void
    {
        // Worse than it looks: the generated client only writes the header when it has a value, so
        // an empty one drops the SDK's identifier entirely.
        $sent = EcommerceApiFactory::make($this->service(), null, '')->getConfig()->getUserAgent();

        self::assertStringContainsString('MyParcelNL-SDK/', $sent);
    }

    public function testResourceCallsCarryTheSdkUserAgent(): void
    {
        $sent = EcommerceApiFactory::make($this->service())->getConfig()->getUserAgent();

        self::assertStringContainsString('MyParcelNL-SDK/', $sent);
        self::assertStringNotContainsString('OpenAPI-Generator', $sent, 'that names the generator, not us');
    }

    public function testAPluginNameGoesInFrontOfTheSdkUserAgent(): void
    {
        $connect = $this->service();
        $connect->setUserAgentForProposition('MyParcelNL-WooCommerce', '5.1.0');

        $sent = EcommerceApiFactory::make($connect)->getConfig()->getUserAgent();

        self::assertStringStartsWith('MyParcelNL-WooCommerce/5.1.0 MyParcelNL-SDK/', $sent);
    }

    public function testAUserAgentArgumentWins(): void
    {
        $sent = EcommerceApiFactory::make($this->service(), null, 'my-app/1.0')->getConfig()->getUserAgent();

        self::assertSame('my-app/1.0', $sent);
    }

    public function testEveryCallGoesThroughTheDpopMiddleware(): void
    {
        // Nothing is stored, so no call can be signed. The middleware signs before it sends, so it
        // refuses here rather than putting an unsigned request on the wire.
        $api = EcommerceApiFactory::make($this->service());

        $this->expectException(ConnectException::class);
        $this->expectExceptionMessage('This shop is not connected to MyParcel');

        $api->webhookOrdersPost([new Order()]);
    }

    public function testTheMiddlewareSitsInsideHttpErrorsSoItSeesA401(): void
    {
        // Pushed outside http_errors instead, a 401 would already be an exception before the retry
        // could run. So the retry working at all is what proves the ordering.
        $this->seedConnectedShop();

        $api    = EcommerceApiFactory::make($this->service($this->refreshResponse()));
        $orders = new MockHandler([
            new Response(401, [], '{"message":"Unauthorized"}'),
            new Response(202, ['Content-Type' => 'application/json'], '[]'),
        ]);

        self::stackOf($api)->setHandler($orders);

        $api->webhookOrdersPost([new Order()]);

        self::assertCount(0, $orders, 'the 401 and the retry, so both answers were used');
    }

    /**
     * The handler stack the factory built.
     *
     * Reflection, because nothing in production needs the stack back and a seam that exists for a
     * test is worse than an invasive test. It reads a property of the generated client, so a
     * regeneration that renames it breaks this loudly rather than quietly.
     */
    private static function stackOf(DefaultApi $api): HandlerStack
    {
        $property = new ReflectionProperty(DefaultApi::class, 'client');
        $property->setAccessible(true);

        return $property->getValue($api)->getConfig('handler');
    }
}
