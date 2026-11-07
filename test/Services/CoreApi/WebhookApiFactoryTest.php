<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\CoreApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Services\CoreApi\WebhookApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class WebhookApiFactoryTest extends TestCase
{
    public function testMakeReturnsTheJaneClient(): void
    {
        $api = WebhookApiFactory::make('test-key');

        $this->assertInstanceOf(Client::class, $api);
    }

    public function testMakeSendsTheKeyHostUserAgentAndHeaders(): void
    {
        $request = [];
        $httpClient = new MockHttpClient(static function (string $method, string $url, array $options) use (&$request): MockResponse {
            $request = ['url' => $url, 'headers' => $options['normalized_headers']];

            return new MockResponse('{"data":{"webhook_subscriptions":[]}}', ['http_code' => 200, 'response_headers' => ['content-type' => 'application/json']]);
        });

        WebhookApiFactory::make('test-key', 'https://api.example.com', 'MyApp/1.0', ['x-dmp-no-tracking' => 'true'], $httpClient)->getWebhookSubscriptions();

        $this->assertSame('https://api.example.com/webhook_subscriptions', $request['url']);
        $this->assertSame(['Authorization: Bearer ' . base64_encode('test-key')], $request['headers']['authorization']);
        $this->assertSame(['User-Agent: MyApp/1.0'], $request['headers']['user-agent']);
        $this->assertSame(['x-dmp-no-tracking: true'], $request['headers']['x-dmp-no-tracking']);
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
