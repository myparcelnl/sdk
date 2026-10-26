<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalPropertiesInterface;
class RefShipmentOptionsInsuranceMaxInsurance implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var mixed
     */
    public $amount;
    public function definedProperties(): array
    {
        return ['amount' => 'amount'];
    }
}