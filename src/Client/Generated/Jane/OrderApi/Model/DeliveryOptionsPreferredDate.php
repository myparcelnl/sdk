<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class DeliveryOptionsPreferredDate implements AdditionalPropertiesInterface
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