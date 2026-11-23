<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentResponsesDropOffPoints implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ShipmentResponsesDropOffPointsData
     */
    public ShipmentResponsesDropOffPointsData $data;
    public function definedProperties(): array
    {
        return ['data' => 'data'];
    }
}