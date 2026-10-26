<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ProductToCreateFulfilment implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var list<ProductToCreateFulfilmentPlatformsItem|string>
     */
    public array $platforms;
    /**
     * @var bool
     */
    public bool $trackInventory;
    public function definedProperties(): array
    {
        return ['platforms' => 'platforms', 'trackInventory' => 'trackInventory'];
    }
}