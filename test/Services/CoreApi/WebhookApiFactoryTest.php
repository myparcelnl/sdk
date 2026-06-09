<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\WebhookApi;
use MyParcelNL\Sdk\Services\CoreApi\WebhookApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class WebhookApiFactoryTest extends TestCase
{
    public function testMakeReturnsWebhookApi(): void
    {
        $api = WebhookApiFactory::make('test-key');

        $this->assertInstanceOf(WebhookApi::class, $api);
    }

    public function testMakeWithHostOverride(): void
    {
        $api = WebhookApiFactory::make('test-key', 'https://custom.host.nl');

        $this->assertInstanceOf(WebhookApi::class, $api);
    }

    public function testMakeWithUserAgent(): void
    {
        $api = WebhookApiFactory::make('test-key', null, 'MyApp/1.0');

        $this->assertInstanceOf(WebhookApi::class, $api);
    }

    public function testMakeThrowsWhenApiKeyIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        WebhookApiFactory::make('');
    }

    public function testDefaultHttpTimeoutConstant(): void
    {
        $this->assertSame(10, WebhookApiFactory::DEFAULT_HTTP_TIMEOUT);
    }
}
