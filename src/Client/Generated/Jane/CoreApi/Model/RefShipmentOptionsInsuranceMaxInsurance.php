<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
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