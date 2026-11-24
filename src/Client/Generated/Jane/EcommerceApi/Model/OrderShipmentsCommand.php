<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

class OrderShipmentsCommand extends CommandEnvelope
{
    /**
     * @var OrderShipmentsOrder
     */
    public OrderShipmentsOrder $order;
    /**
     * @var OrderShipmentsShipment
     */
    public OrderShipmentsShipment $shipment;
    public function definedProperties(): array
    {
        return array_merge(parent::definedProperties(), ['order' => 'order', 'shipment' => 'shipment']);
    }
}