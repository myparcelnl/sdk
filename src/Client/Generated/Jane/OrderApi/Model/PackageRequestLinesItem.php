<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class PackageRequestLinesItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of a line of an order.
     *
     * @var string
     */
    public string $id;
    /**
     * The quantity of the product of the order line.
     *
     * @var int
     */
    public int $quantity;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'quantity' => 'quantity'];
    }
}