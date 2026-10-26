<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var bool
     */
    public bool $isSelectedByDefault;
    /**
     * @var bool
     */
    public bool $isRequired;
    /**
     * Deprecated. Use the flat default/min/max properties on the insurance option directly. Will be removed in a future release.
     * 
     *
     * @var RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1InsuredAmount
     */
    public RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1InsuredAmount $insuredAmount;
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
        return ['isSelectedByDefault' => 'is_selected_by_default', 'isRequired' => 'is_required', 'insuredAmount' => 'insured_amount', 'default' => 'default', 'min' => 'min', 'max' => 'max'];
    }
}