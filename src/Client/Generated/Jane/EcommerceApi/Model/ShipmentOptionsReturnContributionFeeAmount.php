<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsReturnContributionFeeAmount implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * ISO 4217 three-letter currency code.
     *
     * @var string
     */
    public string $currency;
    /**
     * The amount of money in integer micros.
     *
     * @var int
     */
    public int $micros;
    public function definedProperties(): array
    {
        return ['currency' => 'currency', 'micros' => 'micros'];
    }
}