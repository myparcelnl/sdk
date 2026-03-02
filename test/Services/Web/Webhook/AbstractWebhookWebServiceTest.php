<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Web\Webhook;

use MyParcelNL\Sdk\Services\Web\Webhook\AbstractWebhookWebService;
use MyParcelNL\Sdk\Services\Web\Webhook\ShipmentLabelCreatedWebhookWebService;
use MyParcelNL\Sdk\Services\Web\Webhook\ShipmentStatusChangeWebhookWebService;
use MyParcelNL\Sdk\Services\Web\Webhook\ShopCarrierAccessibilityUpdatedWebhookWebService;
use MyParcelNL\Sdk\Services\Web\Webhook\ShopUpdatedWebhookWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AbstractWebhookWebServiceTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideWebhookData(): array
    {
        return [
            'shipment_label_created'             => [new ShipmentLabelCreatedWebhookWebService()],
            'shipment_status_change'             => [new ShipmentStatusChangeWebhookWebService()],
            'shop_carrier_accessibility_updated' => [new ShopCarrierAccessibilityUpdatedWebhookWebService()],
            'shop_updated'                       => [new ShopUpdatedWebhookWebService()],
        ];
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideWebhookData
     */
    public function testSubscribe(AbstractWebhookWebService $webhookWebService): void
    {
        // Mock response for POST /webhook_subscriptions
        $mockResponse = [
            'response' => json_encode([
                'data' => [
                    'ids' => [
                        [
                            'id' => 12345
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $service = (new $webhookWebService())->setApiKey($this->getApiKey());
        $service->subscribe('https://webhook.site');
    }

    /**
     * @param  \MyParcelNL\Sdk\Services\Web\Webhook\AbstractWebhookWebService $webhookWebService
     *
     * @return void
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideWebhookData
     */
    public function testUnsubscribe(AbstractWebhookWebService $webhookWebService): void
    {
        // Mock response for POST /webhook_subscriptions (subscribe call)
        $subscribeMockResponse = [
            'response' => json_encode([
                'data' => [
                    'ids' => [
                        [
                            'id' => 12345
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];

        // Mock response for DELETE /webhook_subscriptions/:id (unsubscribe call)
        $unsubscribeMockResponse = [
            'response' => json_encode([]),
            'headers' => [],
            'code' => 200
        ];

        // Mock the cURL client
        $mockCurl = $this->mockCurl();
        
        // First call: POST subscribe
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($subscribeMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();
        
        // Second call: DELETE unsubscribe
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($unsubscribeMockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $service = (new $webhookWebService())->setApiKey($this->getApiKey());
        $id      = $service->subscribe('https://webhook.site');

        $service->unsubscribe($id);
    }
}
