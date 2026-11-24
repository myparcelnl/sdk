<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShipmentsShipment implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $shipmentId;
    /**
     * @var list<OrderShipmentsOrderLine>
     */
    public array $orderLines;
    public function definedProperties(): array
    {
        return ['shipmentId' => 'shipmentId', 'orderLines' => 'orderLines'];
    }
}