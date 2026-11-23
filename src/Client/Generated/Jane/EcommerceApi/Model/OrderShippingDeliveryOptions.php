<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShippingDeliveryOptions implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Notify sender upon package delivery.
     *
     * @var mixed
     */
    public $notifyOnDelivery;
    /**
     * This option allows you to set a preferred date for delivery, so a drop off date can be calculated.
     *
     * @var OrderShippingDeliveryOptionsPreferredDate
     */
    public OrderShippingDeliveryOptionsPreferredDate $preferredDate;
    public function definedProperties(): array
    {
        return ['notifyOnDelivery' => 'notifyOnDelivery', 'preferredDate' => 'preferredDate'];
    }
}