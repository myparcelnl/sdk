<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web\Webhook;

class ShopUpdatedWebhookWebService extends AbstractWebhookWebService
{
    public function getHook(): string
    {
        return 'shop_updated';
    }
}
