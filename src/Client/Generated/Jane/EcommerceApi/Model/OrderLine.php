<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderLine implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var OrderLineProduct
     */
    public OrderLineProduct $product;
    /**
     * @var int
     */
    public int $quantity;
    /**
     * The line ID as defined in the source system.
     *
     * @var string
     */
    public string $sourceId;
    /**
     * A monetary value represented by split integral and fractional parts to avoid floating point errors.
     *
     * @var Money
     */
    public Money $price;
    public function definedProperties(): array
    {
        return ['product' => 'product', 'quantity' => 'quantity', 'sourceId' => 'sourceId', 'price' => 'price'];
    }
}