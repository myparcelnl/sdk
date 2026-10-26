<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ExternalReferencesMyParcelShop implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ExternalReferencesMyParcelShopSource|string
     */
    public ExternalReferencesMyParcelShopSource|string $source;
    public function definedProperties(): array
    {
        return ['source' => 'source'];
    }
}