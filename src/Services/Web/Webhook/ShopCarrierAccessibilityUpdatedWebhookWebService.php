<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web\Webhook;

class ShopCarrierAccessibilityUpdatedWebhookWebService extends AbstractWebhookWebService
{
    /**
     * @return string
     */
    public function getHook(): string
    {
        return 'shop_carrier_accessibility_updated';
    }
}
