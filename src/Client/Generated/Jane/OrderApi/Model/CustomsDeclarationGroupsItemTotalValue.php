<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class CustomsDeclarationGroupsItemTotalValue implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * ISO 4217 three-letter currency code.
     *
     * @var string
     */
    public string $currency;
    /**
     * The amount of money in integer micros.
     *
     * @var int
     */
    public int $micros;
    public function definedProperties(): array
    {
        return ['currency' => 'currency', 'micros' => 'micros'];
    }
}