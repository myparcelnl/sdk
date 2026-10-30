<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsCommonReturnContributionFee implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The amount of the contribution.
     *
     * @var ShipmentOptionsCommonReturnContributionFeeAmount
     */
    public ShipmentOptionsCommonReturnContributionFeeAmount $amount;
    public function definedProperties(): array
    {
        return ['amount' => 'amount'];
    }
}