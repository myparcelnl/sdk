<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemMoment implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var CommonDefsDateTime|null
     */
    public ?CommonDefsDateTime $start;
    public function definedProperties(): array
    {
        return ['start' => 'start'];
    }
}