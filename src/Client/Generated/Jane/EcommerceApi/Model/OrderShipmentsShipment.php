<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShipmentsShipment implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var list<OrderShipmentsOrderLine>
     */
    public array $orderLines;
    /**
     * @var string
     */
    public string $shipmentId;
    public function definedProperties(): array
    {
        return ['orderLines' => 'orderLines', 'shipmentId' => 'shipmentId'];
    }
}