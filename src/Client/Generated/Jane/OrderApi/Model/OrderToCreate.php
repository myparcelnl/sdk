<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

class OrderToCreate
{
    /**
     * The billing details of an order.
     *
     * @var OrderToCreateBillingDetails
     */
    public OrderToCreateBillingDetails $billingDetails;
    /**
     * The customer reference of the order.
     *
     * @var string
     */
    public string $customerReference;
    /**
     * @var list<OrderLineToCreate>
     */
    public array $lines;
    /**
     * An ISO 8601 formatted datetime string with timezone offset.
     *
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $orderedAt;
    /**
     * The shipping preferences of the order. This should be used as default information when preparing packages for shipment.
     *
     * @var OrderToCreateShipping
     */
    public OrderToCreateShipping $shipping;
    /**
     * The unique identifier of the shop.
     *
     * @var string
     */
    public string $shopId;
    /**
     * The order ID as defined in the source system.
     *
     * @var string
     */
    public string $sourceId;
}