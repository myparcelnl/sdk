<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class PhysicalProperties implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var PhysicalPropertiesHeight
     */
    public PhysicalPropertiesHeight $height;
    /**
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
     * @var PhysicalPropertiesWidth
     */
    public PhysicalPropertiesWidth $width;
    public function definedProperties(): array
    {
        return ['height' => 'height', 'length' => 'length', 'weight' => 'weight', 'width' => 'width'];
    }
}