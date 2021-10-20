<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web\Webhook;

class ShopUpdatedWebhookWebService extends AbstractWebhookWebService
{
    public function getHook(): string
    {
        return 'shop_updated';
    }
}
