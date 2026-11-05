<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class DeliveryOptions implements AdditionalPropertiesInterface
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
     * @var DeliveryOptionsPreferredDate
     */
    public DeliveryOptionsPreferredDate $preferredDate;
    public function definedProperties(): array
    {
        return ['notifyOnDelivery' => 'notifyOnDelivery', 'preferredDate' => 'preferredDate'];
    }
}