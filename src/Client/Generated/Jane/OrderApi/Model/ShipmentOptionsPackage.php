<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsPackage implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Defines a custom text to be printed on the shipping label.
     *
     * @var ShipmentOptionsPackageCustomLabelText
     */
    public ShipmentOptionsPackageCustomLabelText $customLabelText;
    public function definedProperties(): array
    {
        return ['customLabelText' => 'customLabelText'];
    }
}