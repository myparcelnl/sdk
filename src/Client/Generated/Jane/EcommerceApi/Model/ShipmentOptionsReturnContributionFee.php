<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsReturnContributionFee implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * A monetary value represented by split integral and fractional parts to avoid floating point errors.
     *
     * @var Money
     */
    public Money $amount;
    public function definedProperties(): array
    {
        return ['amount' => 'amount'];
    }
}