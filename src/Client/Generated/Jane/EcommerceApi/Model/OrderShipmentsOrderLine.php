<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShipmentsOrderLine implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var int
     */
    public int $quantity;
    /**
     * @var string
     */
    public string $sourceId;
    public function definedProperties(): array
    {
        return ['quantity' => 'quantity', 'sourceId' => 'sourceId'];
    }
}