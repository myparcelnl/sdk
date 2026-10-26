<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentShipmentOptionsReturnsOptionsContribution implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The amount in the lowest denomination of the currency, e.g. cents for EUR.
     *
     * @var int|string
     */
    public $amount;
    /**
     * The currency as uppercase 3-letter ISO 4217 code.
     *
     * @var RefTypesMoneyCurrency|string
     */
    public RefTypesMoneyCurrency|string $currency;
    public function definedProperties(): array
    {
        return ['amount' => 'amount', 'currency' => 'currency'];
    }
}