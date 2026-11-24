<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsReturnContributionFee implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The amount of the contribution.
     *
     * @var ShipmentOptionsReturnContributionFeeAmount
     */
    public ShipmentOptionsReturnContributionFeeAmount $amount;
    public function definedProperties(): array
    {
        return ['amount' => 'amount'];
    }
}