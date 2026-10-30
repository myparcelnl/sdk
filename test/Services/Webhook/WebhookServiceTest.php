<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Webhook;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteWebhookSubscriptionsClientErrorException;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Webhook\WebhookService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class WebhookServiceTest extends TestCase
{
    private const SUBSCRIPTIONS = '{"data":{"webhook_subscriptions":['
        . '{"id":7,"account_id":1,"shop_id":2,"hook":"shipment_status_change","url":"https://example.com/status"},'
        . '{"id":8,"account_id":1,"shop_id":2,"hook":"order_status_change","url":"https://example.com/order"}]}}';

    /**
     * @var array<int, array{method: string, url: string, content_type: string[], user_agent: string[], body: string}>
     */
    private array $requests = [];

    public function testSubscribeSendsTheHookAndUrlAndReturnsTheId(): void
    {
        $service = $this->createService('{"data":{"ids":[{"id":42}]}}');

        $id = $service->subscribe(WebhookService::HOOK_SHIPMENT_STATUS_CHANGE, 'https://example.com/webhook');

        self::assertSame(42, $id);
        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/webhook_subscriptions', $this->requests[0]['url']);
        self::assertSame(['application/json;version=1.1'], $this->requests[0]['content_type']);
        self::assertSame(['User-Agent: ' . $service->getUserAgentHeader()], $this->requests[0]['user_agent']);
        self::assertJsonStringEqualsJsonString(
            '{"data":{"webhook_subscriptions":[{"hook":"shipment_status_change","url":"https://example.com/webhook"}]}}',
            $this->requests[0]['body']
        );
    }

    public function testSubscribeThrowsWhenTheResponseHasNoIds(): void
    {
        $service = $this->createService('{"data":{"ids":[]}}');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unexpected response: no subscription IDs returned.');

        $service->subscribe(WebhookService::HOOK_SHOP_UPDATED, 'https://example.com/shop');
    }

    public function testUnsubscribeSendsTheIdsSeparatedBySemicolons(): void
    {
        $service = $this->createService('', 204);

        $service->unsubscribe(10, 20, 30);

        self::assertSame('DELETE', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/webhook_subscriptions/10%3B20%3B30', $this->requests[0]['url']);
    }

    public function testUnsubscribeReturnsEarlyOnNoIds(): void
    {
        $this->createService('', 204)->unsubscribe();

        self::assertSame([], $this->requests);
    }

    public function testAnErrorResponseThrowsTheGeneratedException(): void
    {
        $service = $this->createService('{"message":"Not found"}', 404);

        try {
            $service->unsubscribe(10);
            self::fail('A 404 response must throw.');
        } catch (DeleteWebhookSubscriptionsClientErrorException $exception) {
            self::assertSame(404, $exception->getResponse()->getStatusCode());
        }
    }

    public function testGetAllReturnsSubscriptions(): void
    {
        $service = $this->createService(self::SUBSCRIPTIONS);

        $result = $service->getAll();

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/webhook_subscriptions', $this->requests[0]['url']);
        self::assertCount(2, $result);
        self::assertSame(7, $result[0]->id);
        self::assertSame('shipment_status_change', $result[0]->hook);
        self::assertSame('order_status_change', $result[1]->hook);
    }

    public function testGetAllWithHookFilterSendsTheHook(): void
    {
        $service = $this->createService('{"data":{"webhook_subscriptions":[]}}');

        $result = $service->getAll(WebhookService::HOOK_SHIPMENT_LABEL_CREATED);

        self::assertSame('https://api.myparcel.nl/webhook_subscriptions?hook=shipment_label_created', $this->requests[0]['url']);
        self::assertSame([], $result);
    }

    public function testGetByIdReturnsSubscriptions(): void
    {
        $service = $this->createService(
            '{"data":{"webhook_subscriptions":[{"id":99,"account_id":1,"shop_id":2,"hook":"shop_updated","url":"https://example.com/shop"}]}}'
        );

        $result = $service->getById(99);

        self::assertSame('https://api.myparcel.nl/webhook_subscriptions/99', $this->requests[0]['url']);
        self::assertCount(1, $result);
        self::assertSame('shop_updated', $result[0]->hook);
    }

    public function testGetByIdReturnsEmptyOnNoIds(): void
    {
        self::assertSame([], $this->createService(self::SUBSCRIPTIONS)->getById());
        self::assertSame([], $this->requests);
    }

    public function testHookConstantsMatchLegacyValues(): void
    {
        self::assertSame('shipment_status_change', WebhookService::HOOK_SHIPMENT_STATUS_CHANGE);
        self::assertSame('shipment_label_created', WebhookService::HOOK_SHIPMENT_LABEL_CREATED);
        self::assertSame('order_status_change', WebhookService::HOOK_ORDER_STATUS_CHANGE);
        self::assertSame('shop_carrier_accessibility_updated', WebhookService::HOOK_SHOP_CARRIER_ACCESSIBILITY_UPDATED);
        self::assertSame('shop_updated', WebhookService::HOOK_SHOP_UPDATED);
    }

    private function createService(string $body, int $status = 200): WebhookService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status): MockResponse {
            $this->requests[] = [
                'method'       => $method,
                'url'          => $url,
                'content_type' => array_map(static fn(string $line): string => substr($line, 14), $options['normalized_headers']['content-type'] ?? []),
                'user_agent'   => $options['normalized_headers']['user-agent'] ?? [],
                'body'         => (string) ($options['body'] ?? ''),
            ];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => 'application/json']]);
        });

        return new WebhookService('my-api-key', JaneClientFactory::coreApi('my-api-key', null, null, [], $httpClient));
    }
}
