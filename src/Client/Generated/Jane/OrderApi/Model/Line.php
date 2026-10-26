<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class Line implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of a line of an order.
     *
     * @var string
     */
    public string $id;
    /**
     * The product of an order line.
     *
     * @var LineProduct
     */
    public LineProduct $product;
    /**
     * The quantity of the product of the order line.
     *
     * @var int
     */
    public int $quantity;
    /**
     * The external reference of the order line.
     *
     * @var LineExternalReference
     */
    public LineExternalReference $externalReference;
    /**
     * @var LinePrice
     */
    public LinePrice $price;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'product' => 'product', 'quantity' => 'quantity', 'externalReference' => 'externalReference', 'price' => 'price'];
    }
}