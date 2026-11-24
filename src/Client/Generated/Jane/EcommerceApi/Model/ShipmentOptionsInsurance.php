<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsInsurance implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The amount to insure.
     *
     * @var ShipmentOptionsInsuranceAmount
     */
    public ShipmentOptionsInsuranceAmount $amount;
    public function definedProperties(): array
    {
        return ['amount' => 'amount'];
    }
}