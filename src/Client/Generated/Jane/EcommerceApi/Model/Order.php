<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class Order implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * An ISO 8601 formatted datetime string with timezone offset.
     *
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $orderedAt;
    /**
     * The order ID as defined in the source system.
     *
     * @var string
     */
    public string $sourceId;
    /**
     * The order status.
     *
     * @var OrderStatus|string
     */
    public OrderStatus|string $status;
    /**
     * Billing details of the order.
     *
     * @var BillingDetails
     */
    public BillingDetails $billingDetails;
    /**
     * The customer reference of the order.
     *
     * @var string
     */
    public string $customerReference;
    /**
     * @var list<OrderLine>
     */
    public array $lines;
    /**
     * @var OrderShipping
     */
    public OrderShipping $shipping;
    public function definedProperties(): array
    {
        return ['orderedAt' => 'orderedAt', 'sourceId' => 'sourceId', 'status' => 'status', 'billingDetails' => 'billingDetails', 'customerReference' => 'customerReference', 'lines' => 'lines', 'shipping' => 'shipping'];
    }
}