<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentDefsOccupancy implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var float|null
     */
    public ?float $monday;
    /**
     * @var float|null
     */
    public ?float $tuesday;
    /**
     * @var float|null
     */
    public ?float $wednesday;
    /**
     * @var float|null
     */
    public ?float $thursday;
    /**
     * @var float|null
     */
    public ?float $friday;
    /**
     * @var float|null
     */
    public ?float $saturday;
    /**
     * @var float|null
     */
    public ?float $sunday;
    public function definedProperties(): array
    {
        return ['monday' => 'monday', 'tuesday' => 'tuesday', 'wednesday' => 'wednesday', 'thursday' => 'thursday', 'friday' => 'friday', 'saturday' => 'saturday', 'sunday' => 'sunday'];
    }
}