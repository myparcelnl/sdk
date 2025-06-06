<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web\Webhook;

class ShipmentLabelCreatedWebhookWebService extends AbstractWebhookWebService
{
    public function getHook(): string
    {
        return 'shipment_label_created';
    }
}
