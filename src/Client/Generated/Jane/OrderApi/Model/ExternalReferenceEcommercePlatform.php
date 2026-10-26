<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ExternalReferenceEcommercePlatform implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The identifier of the external e-commerce platform.
     *
     * @var ExternalReferenceEcommercePlatformName|string
     */
    public ExternalReferenceEcommercePlatformName|string $name;
    /**
     * The order ID as defined in the source system.
     *
     * @var string
     */
    public string $sourceId;
    public function definedProperties(): array
    {
        return ['name' => 'name', 'sourceId' => 'sourceId'];
    }
}