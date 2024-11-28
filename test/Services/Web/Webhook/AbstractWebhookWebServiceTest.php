<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Web\Webhook;

use MyParcelNL\Sdk\src\Services\Web\Webhook\AbstractWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShipmentLabelCreatedWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShipmentStatusChangeWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShopCarrierAccessibilityUpdatedWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShopCarrierConfigurationUpdatedWebhookWebService;
use MyParcelNL\Sdk\src\Services\Web\Webhook\ShopUpdatedWebhookWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AbstractWebhookWebServiceTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public static function provideWebhookData(): array
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
     * @throws \Exception
     * @dataProvider provideWebhookData
     * @doesNotPerformAssertions
     */
    public function testSubscribe(AbstractWebhookWebService $webhookWebService): void
    {
        $service = (new $webhookWebService())->setApiKey($this->getApiKey());
        $service->subscribe('https://webhook.site');
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Services\Web\Webhook\AbstractWebhookWebService $webhookWebService
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideWebhookData
     * @doesNotPerformAssertions
     */
    public function testUnsubscribe(AbstractWebhookWebService $webhookWebService): void
    {
        $service = (new $webhookWebService())->setApiKey($this->getApiKey());
        $id      = $service->subscribe('https://webhook.site');

        $service->unsubscribe($id);
    }
}
