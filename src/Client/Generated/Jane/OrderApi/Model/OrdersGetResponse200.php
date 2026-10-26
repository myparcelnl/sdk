<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrdersGetResponse200 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The list of orders.
     *
     * @var list<Order>
     */
    public array $results;
    /**
     * @var OrdersGetResponse200Total
     */
    public OrdersGetResponse200Total $total;
    /**
     * Count of orders per status, scoped to the active search query and all active filters except the status filter.
     *
     * @var OrdersGetResponse200Aggregations
     */
    public OrdersGetResponse200Aggregations $aggregations;
    /**
     * The next page token to retrieve the next page of orders.
     *
     * @var string
     */
    public string $nextPageToken;
    public function definedProperties(): array
    {
        return ['results' => 'results', 'total' => 'total', 'aggregations' => 'aggregations', 'nextPageToken' => 'nextPageToken'];
    }
}