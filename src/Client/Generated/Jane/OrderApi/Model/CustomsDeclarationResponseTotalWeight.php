<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class CustomsDeclarationResponseTotalWeight implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The total weight of all items declared within this customs declaration.
     *
     * @var CustomsDeclarationResponseTotalWeightUnit|string
     */
    public CustomsDeclarationResponseTotalWeightUnit|string $unit;
    /**
     * @var int
     */
    public int $value;
    public function definedProperties(): array
    {
        return ['unit' => 'unit', 'value' => 'value'];
    }
}