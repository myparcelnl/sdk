<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShippingDelivery implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Options regarding delivery timing and notifications.
     *
     * @var OrderShippingDeliveryOptions
     */
    public OrderShippingDeliveryOptions $options;
    /**
     * The delivery type of the shipment.
     *
     * @var OrderShippingDeliveryType|string
     */
    public OrderShippingDeliveryType|string $type;
    public function definedProperties(): array
    {
        return ['options' => 'options', 'type' => 'type'];
    }
}