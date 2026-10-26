<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsPackageCustomLabelText implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Allows adding personalized text to be printed on the shipping label, which can include special handling instructions or reference information.
     *
     * @var string
     */
    public string $text;
    public function definedProperties(): array
    {
        return ['text' => 'text'];
    }
}