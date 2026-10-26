<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalPropertiesInterface;
class RefShipmentOptionsInsuranceMax implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefTypesPriceEuro
     */
    public RefTypesPriceEuro $insurance;
    public function definedProperties(): array
    {
        return ['insurance' => 'insurance'];
    }
}