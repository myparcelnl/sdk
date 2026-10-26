<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentShipmentOptionsReturns implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefShipmentShipmentOptionsReturnsOptions
     */
    public RefShipmentShipmentOptionsReturnsOptions $options;
    public function definedProperties(): array
    {
        return ['options' => 'options'];
    }
}