<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesResponseOptionsInsuranceOptionV2 implements AdditionalPropertiesInterface
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
     * camelCase shipment option keys that are required
     *
     * @var list<string>
     */
    public array $requires;
    /**
     * camelCase shipment option keys that are excluded
     *
     * @var list<string>
     */
    public array $excludes;
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
        return ['isSelectedByDefault' => 'isSelectedByDefault', 'isRequired' => 'isRequired', 'requires' => 'requires', 'excludes' => 'excludes', 'insuredAmount' => 'insuredAmount', 'default' => 'default', 'min' => 'min', 'max' => 'max'];
    }
}