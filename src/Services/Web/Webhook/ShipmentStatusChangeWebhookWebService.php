<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web\Webhook;

class ShipmentStatusChangeWebhookWebService extends AbstractWebhookWebService
{
    public function getHook(): string
    {
        return 'shipment_status_change';
    }
}
