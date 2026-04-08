<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Webhook;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\WebhookApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptions;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptionsData;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptionsDataIdsInner;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11Data;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookV11;
use MyParcelNL\Sdk\Services\Webhook\WebhookService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class WebhookServiceTest extends TestCase
{
    public function testSubscribeCallsPostAndReturnsId(): void
    {
        $expectedId = 42;

        $idObj = new WebhooksResponsesPostWebhookSubscriptionsDataIdsInner(['id' => $expectedId]);
        $data  = new WebhooksResponsesPostWebhookSubscriptionsData(['ids' => [$idObj]]);
        $response = new WebhooksResponsesPostWebhookSubscriptions(['data' => $data]);

        $api = $this->createMock(WebhookApi::class);
        $api->expects(self::once())
            ->method('postWebhookSubscriptions')
            ->with(
                self::isType('string'),
                self::callback(static function (WebhooksPostWebhookSubscriptionsRequestV11 $request): bool {
                    $subs = $request->getData()->getWebhookSubscriptions();
                    self::assertCount(1, $subs);
                    self::assertSame('shipment_status_change', $subs[0]->getHook());
                    self::assertSame('https://example.com/webhook', $subs[0]->getUrl());

                    return true;
                })
            )
            ->willReturn($response);

        $service = new WebhookService($this->getApiKey(), $api);
        $id = $service->subscribe(
            WebhookService::HOOK_SHIPMENT_STATUS_CHANGE,
            'https://example.com/webhook'
        );

        self::assertSame($expectedId, $id);
    }

    public function testUnsubscribeCallsDeleteWithSemicolonSeparatedIds(): void
    {
        $api = $this->createMock(WebhookApi::class);
        $api->expects(self::once())
            ->method('deleteWebhookSubscriptions')
            ->with(
                self::identicalTo('10;20;30'),
                self::isType('string')
            );

        $service = new WebhookService($this->getApiKey(), $api);
        $service->unsubscribe(10, 20, 30);
    }

    public function testUnsubscribeReturnsEarlyOnNoIds(): void
    {
        $api = $this->createMock(WebhookApi::class);
        $api->expects(self::never())
            ->method('deleteWebhookSubscriptions');

        $service = new WebhookService($this->getApiKey(), $api);
        $service->unsubscribe();
    }

    public function testGetAllReturnsSubscriptions(): void
    {
        $webhook1 = new WebhooksResponsesWebhookV11([
            'id'   => 1,
            'hook' => 'shipment_status_change',
            'url'  => 'https://example.com/hook1',
        ]);
        $webhook2 = new WebhooksResponsesWebhookV11([
            'id'   => 2,
            'hook' => 'order_status_change',
            'url'  => 'https://example.com/hook2',
        ]);

        $data     = new WebhooksResponsesWebhookSubscriptionsV11Data(['webhook_subscriptions' => [$webhook1, $webhook2]]);
        $response = new WebhooksResponsesWebhookSubscriptionsV11(['data' => $data]);

        $api = $this->createMock(WebhookApi::class);
        $api->expects(self::once())
            ->method('getWebhookSubscriptions')
            ->with(self::isType('string'), self::isNull())
            ->willReturn($response);

        $service = new WebhookService($this->getApiKey(), $api);
        $result  = $service->getAll();

        self::assertCount(2, $result);
        self::assertSame('shipment_status_change', $result[0]->getHook());
        self::assertSame('order_status_change', $result[1]->getHook());
    }

    public function testGetAllWithHookFilterPassesHookToApi(): void
    {
        $data     = new WebhooksResponsesWebhookSubscriptionsV11Data(['webhook_subscriptions' => []]);
        $response = new WebhooksResponsesWebhookSubscriptionsV11(['data' => $data]);

        $api = $this->createMock(WebhookApi::class);
        $api->expects(self::once())
            ->method('getWebhookSubscriptions')
            ->with(
                self::isType('string'),
                self::identicalTo('shipment_label_created')
            )
            ->willReturn($response);

        $service = new WebhookService($this->getApiKey(), $api);
        $result  = $service->getAll(WebhookService::HOOK_SHIPMENT_LABEL_CREATED);

        self::assertSame([], $result);
    }

    public function testGetByIdReturnsSubscriptions(): void
    {
        $webhook = new WebhooksResponsesWebhookV11([
            'id'   => 99,
            'hook' => 'shop_updated',
            'url'  => 'https://example.com/shop',
        ]);

        $data     = new WebhooksResponsesWebhookSubscriptionsV11Data(['webhook_subscriptions' => [$webhook]]);
        $response = new WebhooksResponsesWebhookSubscriptionsV11(['data' => $data]);

        $api = $this->createMock(WebhookApi::class);
        $api->expects(self::once())
            ->method('getWebhookSubscriptionsById')
            ->with(
                self::identicalTo('99'),
                self::isType('string')
            )
            ->willReturn($response);

        $service = new WebhookService($this->getApiKey(), $api);
        $result  = $service->getById(99);

        self::assertCount(1, $result);
        self::assertSame('shop_updated', $result[0]->getHook());
    }

    public function testGetByIdReturnsEmptyOnNoIds(): void
    {
        $api = $this->createMock(WebhookApi::class);
        $api->expects(self::never())
            ->method('getWebhookSubscriptionsById');

        $service = new WebhookService($this->getApiKey(), $api);
        $result  = $service->getById();

        self::assertSame([], $result);
    }

    public function testHookConstantsMatchLegacyValues(): void
    {
        self::assertSame('shipment_status_change', WebhookService::HOOK_SHIPMENT_STATUS_CHANGE);
        self::assertSame('shipment_label_created', WebhookService::HOOK_SHIPMENT_LABEL_CREATED);
        self::assertSame('order_status_change', WebhookService::HOOK_ORDER_STATUS_CHANGE);
        self::assertSame('shop_carrier_accessibility_updated', WebhookService::HOOK_SHOP_CARRIER_ACCESSIBILITY_UPDATED);
        self::assertSame('shop_updated', WebhookService::HOOK_SHOP_UPDATED);
    }
}
