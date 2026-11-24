<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShipmentsOrder implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $sourceId;
    /**
     * @var OrderShipmentsOrderStatus|string
     */
    public OrderShipmentsOrderStatus|string $status;
    public function definedProperties(): array
    {
        return ['sourceId' => 'sourceId', 'status' => 'status'];
    }
}