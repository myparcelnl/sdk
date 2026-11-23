<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class Delivery implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Options regarding delivery timing and notifications.
     *
     * @var DeliveryOptions
     */
    public DeliveryOptions $options;
    /**
     * The delivery type of the shipment.
     *
     * @var DeliveryType|string
     */
    public DeliveryType|string $type;
    public function definedProperties(): array
    {
        return ['options' => 'options', 'type' => 'type'];
    }
}