<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class PhysicalPropertiesWeightRequired implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The height.
     *
     * @var PhysicalPropertiesWeightRequiredHeight
     */
    public PhysicalPropertiesWeightRequiredHeight $height;
    /**
     * The length.
     *
     * @var PhysicalPropertiesWeightRequiredLength
     */
    public PhysicalPropertiesWeightRequiredLength $length;
    /**
     * The physical weight.
     *
     * @var PhysicalPropertiesWeightRequiredWeight
     */
    public PhysicalPropertiesWeightRequiredWeight $weight;
    /**
     * The width.
     *
     * @var PhysicalPropertiesWeightRequiredWidth
     */
    public PhysicalPropertiesWeightRequiredWidth $width;
    public function definedProperties(): array
    {
        return ['height' => 'height', 'length' => 'length', 'weight' => 'weight', 'width' => 'width'];
    }
}