<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\Adapter\DeliveryOptions\DeliveryOptionsFromOrderAdapter;
use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Model\Recipient;
use MyParcelNL\Sdk\Support\Collection;

class Order extends AbstractOrder
{
    /*
     * Allows setting an API key for each order, so you can create multiple
     * orders for different shops at the same time. This way you won't have to provide a shop ID.
     */
    use HasApiKey;

    /**
     * @param  array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        $this->uuid                          = $data['uuid'] ?? null;
        $this->external_identifier           = $data['external_identifier'] ?? null;
        $this->fulfilment_partner_identifier = $data['fulfilment_partner_identifier'] ?? null;
        $this->language                      = $data['language'] ?? null;
        $this->order_date                    = new DateTime($data['order_date'] ?? 'now');
        $this->order_shipments               = $data['order_shipments'] ?? [];
        $this->status                        = $data['status'] ?? null;
        $this->type                          = $data['type'] ?? null;

        $this->recipient        = new Recipient($data['shipment']['recipient'] ?? []);
        $this->invoice_address  = new Recipient($data['invoice_address'] ?? []);
        $this->order_lines      = (new Collection($data['order_lines'] ?? []))->mapInto(OrderLine::class);
        $this->delivery_options = new DeliveryOptionsFromOrderAdapter($data['shipment'] ?? []);
    }
}
