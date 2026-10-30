<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsCommonInsurance implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The amount to insure.
     *
     * @var ShipmentOptionsCommonInsuranceAmount
     */
    public ShipmentOptionsCommonInsuranceAmount $amount;
    public function definedProperties(): array
    {
        return ['amount' => 'amount'];
    }
}