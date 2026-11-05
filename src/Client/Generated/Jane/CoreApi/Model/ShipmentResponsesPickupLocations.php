<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentResponsesPickupLocations implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ShipmentResponsesPickupLocationsData
     */
    public ShipmentResponsesPickupLocationsData $data;
    public function definedProperties(): array
    {
        return ['data' => 'data'];
    }
}