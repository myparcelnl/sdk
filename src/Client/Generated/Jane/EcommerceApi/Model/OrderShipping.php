<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShipping implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Additional shipping context of the order.
     *
     * @var string
     */
    public string $additionalContext;
    /**
     * The carrier to select to ship a package with.
     *
     * @var Carrier|string
     */
    public Carrier|string $carrier;
    /**
     * The delivery details of the shipment.
     *
     * @var OrderShippingDelivery
     */
    public OrderShippingDelivery $delivery;
    /**
     * The complete set of shipment options.
     *
     * @var ShipmentOptions
     */
    public ShipmentOptions $options;
    /**
     * The type of the package.
     *
     * @var OrderShippingPackageType|string
     */
    public OrderShippingPackageType|string $packageType;
    /**
     * The pickup details.
     *
     * @var mixed
     */
    public $pickup;
    /**
     * The recipient of the shipment.
     *
     * @var OrderShippingRecipient
     */
    public OrderShippingRecipient $recipient;
    public function definedProperties(): array
    {
        return ['additionalContext' => 'additionalContext', 'carrier' => 'carrier', 'delivery' => 'delivery', 'options' => 'options', 'packageType' => 'packageType', 'pickup' => 'pickup', 'recipient' => 'recipient'];
    }
}