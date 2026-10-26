<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class PhysicalProperties implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The height.
     *
     * @var PhysicalPropertiesHeight
     */
    public PhysicalPropertiesHeight $height;
    /**
     * The length.
     *
     * @var PhysicalPropertiesLength
     */
    public PhysicalPropertiesLength $length;
    /**
     * The weight.
     *
     * @var PhysicalPropertiesWeight
     */
    public PhysicalPropertiesWeight $weight;
    /**
     * The width.
     *
     * @var PhysicalPropertiesWidth
     */
    public PhysicalPropertiesWidth $width;
    public function definedProperties(): array
    {
        return ['height' => 'height', 'length' => 'length', 'weight' => 'weight', 'width' => 'width'];
    }
}