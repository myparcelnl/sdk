<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $weight;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $width;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $length;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $height;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $girth;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $volumetricWeight;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $volume;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty $lengthWidthHeightSum;
    public function definedProperties(): array
    {
        return ['weight' => 'weight', 'width' => 'width', 'length' => 'length', 'height' => 'height', 'girth' => 'girth', 'volumetricWeight' => 'volumetric_weight', 'volume' => 'volume', 'lengthWidthHeightSum' => 'length_width_height_sum'];
    }
}