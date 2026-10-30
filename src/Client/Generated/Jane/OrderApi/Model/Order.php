<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class Order implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the order.
     *
     * @var string
     */
    public string $id;
    /**
     * The date and time the order was placed.
     *
     * @var mixed
     */
    public $orderedAt;
    /**
     * A monetary value represented by split integral and fractional parts to avoid floating point errors.
     *
     * @var Money
     */
    public Money $price;
    /**
     * The unique identifier of the shop.
     *
     * @var string
     */
    public string $shopId;
    /**
     * The short ID of the order.
     *
     * @var string
     */
    public string $shortId;
    /**
     * The status of the order.
     *
     * @var OrderStatus|string
     */
    public OrderStatus|string $status;
    /**
     * The user ID assigned to the order.
     *
     * @var string
     */
    public string $assignedUserId;
    /**
     * The billing details of an order.
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
     * @var mixed
     */
    public $externalReferences;
    /**
     * The lines of the order
     *
     * @var list<Line>
     */
    public array $lines;
    /**
     * The notes of the order.
     *
     * @var list<NoteFromImported>|list<NoteFromPrincipal>
     */
    public array $notes;
    /**
     * The packages of the order.
     *
     * @var list<PackageResponse>
     */
    public array $packages;
    /**
     * The rules that have been applied to the order.
     *
     * @var OrderRules
     */
    public OrderRules $rules;
    /**
     * The shipping preferences of the order. This should be used as default information when preparing packages for shipment.
     *
     * @var Shipping
     */
    public Shipping $shipping;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'orderedAt' => 'orderedAt', 'price' => 'price', 'shopId' => 'shopId', 'shortId' => 'shortId', 'status' => 'status', 'assignedUserId' => 'assignedUserId', 'billingDetails' => 'billingDetails', 'customerReference' => 'customerReference', 'externalReferences' => 'externalReferences', 'lines' => 'lines', 'notes' => 'notes', 'packages' => 'packages', 'rules' => 'rules', 'shipping' => 'shipping'];
    }
}