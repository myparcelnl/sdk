<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderToCreateShippingOptionsReturnContributionFee implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The amount of the contribution.
     *
     * @var OrderToCreateShippingOptionsReturnContributionFeeAmount
     */
    public OrderToCreateShippingOptionsReturnContributionFeeAmount $amount;
    public function definedProperties(): array
    {
        return ['amount' => 'amount'];
    }
}