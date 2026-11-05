<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShipmentMulticollo implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier for a multicollo shipment group.
     *
     * @var string
     */
    public string $id;
    /**
     * The total number of individual packages included within this specific multicollo shipment group.
     *
     * @var int
     */
    public int $totalPackages;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'totalPackages' => 'totalPackages'];
    }
}