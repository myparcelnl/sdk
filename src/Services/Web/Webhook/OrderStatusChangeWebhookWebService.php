<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web\Webhook;

class OrderStatusChangeWebhookWebService extends AbstractWebhookWebService
{
    public function getHook(): string
    {
        return 'order_status_change';
    }
}
