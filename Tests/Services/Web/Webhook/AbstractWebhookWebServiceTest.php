<?php

declare(strict_types=1);

namespace Services\Web\Webhook;

use MyParcelNL\Sdk\src\Services\Web\Webhook\AbstractWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShipmentLabelCreatedWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShipmentStatusChangeWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShopCarrierAccessibilityUpdatedWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShopCarrierConfigurationUpdatedWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShopUpdatedWebhookWebService;
use PHPUnit\Framework\TestCase;

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
            'shop_carrier_configuration_updated' => [new ShopCarrierConfigurationUpdatedWebhookWebService()],
            'shop_updated'                       => [new ShopUpdatedWebhookWebService()],
        ];
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideWebhookData
     */
    public function testSubscribe(AbstractWebhookWebService $webhookWebService): void
    {
        $service = (new $webhookWebService())->setApiKey(getenv('API_KEY'));
        $service->subscribe('https://webhook.site');
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Services\Web\Webhook\AbstractWebhookWebService $webhookWebService
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @dataProvider provideWebhookData
     */
    public function testUnsubscribe(AbstractWebhookWebService $webhookWebService): void
    {
        $service = (new $webhookWebService())->setApiKey(getenv('API_KEY'));
        $id      = $service->subscribe('https://webhook.site');

        $service->unsubscribe($id);
    }
}
