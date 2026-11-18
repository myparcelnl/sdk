<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Deprecated. Use the flat default/min/max properties on the insurance option directly. Will be removed in a future release.
     * 
     *
     * @var RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmount
     */
    public RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmount $insuredAmount;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $default;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $min;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $max;
    public function definedProperties(): array
    {
        return ['insuredAmount' => 'insuredAmount', 'default' => 'default', 'min' => 'min', 'max' => 'max'];
    }
}