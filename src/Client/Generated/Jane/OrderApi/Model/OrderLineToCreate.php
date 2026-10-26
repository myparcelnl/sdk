<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderLineToCreate implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * A monetary value represented by split integral and fractional parts to avoid floating point errors.
     *
     * @var Money
     */
    public Money $price;
    /**
     * @var ProductToCreate
     */
    public ProductToCreate $product;
    /**
     * @var int
     */
    public int $quantity;
    public function definedProperties(): array
    {
        return ['price' => 'price', 'product' => 'product', 'quantity' => 'quantity'];
    }
}