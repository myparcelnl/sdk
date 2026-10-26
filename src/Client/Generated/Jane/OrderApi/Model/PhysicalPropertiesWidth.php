<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class PhysicalPropertiesWidth implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unit of measurement for length, width, or height.
     *
     * @var PhysicalPropertiesWidthUnit|string
     */
    public PhysicalPropertiesWidthUnit|string $unit;
    /**
     * The dimension value.
     *
     * @var int
     */
    public int $value;
    public function definedProperties(): array
    {
        return ['unit' => 'unit', 'value' => 'value'];
    }
}