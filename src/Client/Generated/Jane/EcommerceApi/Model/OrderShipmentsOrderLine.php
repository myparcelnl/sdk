<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShipmentsOrderLine implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $sourceId;
    /**
     * @var int
     */
    public int $quantity;
    public function definedProperties(): array
    {
        return ['sourceId' => 'sourceId', 'quantity' => 'quantity'];
    }
}