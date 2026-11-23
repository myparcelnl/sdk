<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentOptionsIncoterms implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The Incoterms® that apply to the Shipment.
     * 
     * When omitted, the default rule of the carrier applies.
     * 
     *
     * @var RefShipmentIncoterms
     */
    public RefShipmentIncoterms $incoterms;
    public function definedProperties(): array
    {
        return ['incoterms' => 'incoterms'];
    }
}