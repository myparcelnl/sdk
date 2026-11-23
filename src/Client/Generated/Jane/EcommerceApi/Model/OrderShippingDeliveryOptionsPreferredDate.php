<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShippingDeliveryOptionsPreferredDate implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Recipient specified preferred delivery date.
     *
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $date;
    public function definedProperties(): array
    {
        return ['date' => 'date'];
    }
}