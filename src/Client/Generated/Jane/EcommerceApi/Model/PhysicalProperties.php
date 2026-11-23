<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class PhysicalProperties implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The physical dimension.
     *
     * @var Dimension
     */
    public Dimension $height;
    /**
     * The physical dimension.
     *
     * @var Dimension
     */
    public Dimension $length;
    /**
     * The weight.
     *
     * @var PhysicalPropertiesWeight
     */
    public PhysicalPropertiesWeight $weight;
    /**
     * The physical dimension.
     *
     * @var Dimension
     */
    public Dimension $width;
    public function definedProperties(): array
    {
        return ['height' => 'height', 'length' => 'length', 'weight' => 'weight', 'width' => 'width'];
    }
}