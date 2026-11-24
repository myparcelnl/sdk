<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptionsCustomLabelText implements AdditionalPropertiesInterface
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