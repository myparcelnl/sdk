<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web\Webhook;

class ShopCarrierConfigurationUpdatedWebhookWebService extends AbstractWebhookWebService
{
    /**
     * @return string
     */
    public function getHook(): string
    {
        return 'shop_carrier_configuration_updated';
    }
}
