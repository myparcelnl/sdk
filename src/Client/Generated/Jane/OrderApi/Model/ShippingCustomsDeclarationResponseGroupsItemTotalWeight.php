<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShippingCustomsDeclarationResponseGroupsItemTotalWeight implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unit of weight.
     *
     * @var ShippingCustomsDeclarationResponseGroupsItemTotalWeightUnit|string
     */
    public ShippingCustomsDeclarationResponseGroupsItemTotalWeightUnit|string $unit;
    /**
     * @var int
     */
    public int $value;
    public function definedProperties(): array
    {
        return ['unit' => 'unit', 'value' => 'value'];
    }
}