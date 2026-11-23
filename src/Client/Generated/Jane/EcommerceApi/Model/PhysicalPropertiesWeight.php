<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class PhysicalPropertiesWeight implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unit of weight.
     *
     * @var PhysicalPropertiesWeightUnit|string
     */
    public PhysicalPropertiesWeightUnit|string $unit;
    /**
     * @var int
     */
    public int $value;
    public function definedProperties(): array
    {
        return ['unit' => 'unit', 'value' => 'value'];
    }
}