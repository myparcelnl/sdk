<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrdersGetResponse200AggregationsStatusItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The status of the order.
     *
     * @var OrderStatus|string
     */
    public OrderStatus|string $key;
    /**
     * @var int
     */
    public int $count;
    public function definedProperties(): array
    {
        return ['key' => 'key', 'count' => 'count'];
    }
}