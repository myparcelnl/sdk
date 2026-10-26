<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ProductToCreatePhysicalProperties implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The height.
     *
     * @var ProductToCreatePhysicalPropertiesHeight
     */
    public ProductToCreatePhysicalPropertiesHeight $height;
    /**
     * The length.
     *
     * @var ProductToCreatePhysicalPropertiesLength
     */
    public ProductToCreatePhysicalPropertiesLength $length;
    /**
     * The weight.
     *
     * @var ProductToCreatePhysicalPropertiesWeight
     */
    public ProductToCreatePhysicalPropertiesWeight $weight;
    /**
     * The width.
     *
     * @var ProductToCreatePhysicalPropertiesWidth
     */
    public ProductToCreatePhysicalPropertiesWidth $width;
    public function definedProperties(): array
    {
        return ['height' => 'height', 'length' => 'length', 'weight' => 'weight', 'width' => 'width'];
    }
}