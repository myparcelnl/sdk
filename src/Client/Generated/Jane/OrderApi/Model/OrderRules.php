<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderRules implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var OrderRulesSetShippingPreferences
     */
    public OrderRulesSetShippingPreferences $setShippingPreferences;
    public function definedProperties(): array
    {
        return ['setShippingPreferences' => 'setShippingPreferences'];
    }
}