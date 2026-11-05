<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrdersGetResponse200Total implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The relation of the total number of orders.
     *
     * @var OrdersGetResponse200TotalRelation|string
     */
    public OrdersGetResponse200TotalRelation|string $relation;
    /**
     * The total number of orders.
     *
     * @var int
     */
    public int $value;
    public function definedProperties(): array
    {
        return ['relation' => 'relation', 'value' => 'value'];
    }
}