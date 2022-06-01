<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsFromOrderAdapter;
use MyParcelNL\Sdk\src\Model\Recipient;
use MyParcelNL\Sdk\src\Support\Collection;

class Order extends AbstractOrder
{
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
