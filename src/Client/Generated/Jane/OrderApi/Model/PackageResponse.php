<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class PackageResponse implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the package.
     *
     * @var string
     */
    public string $id;
    /**
     * @var mixed
     */
    public $status;
    /**
     * @var PhysicalProperties
     */
    public PhysicalProperties $physicalProperties;
    /**
     * The order lines related to the package.
     *
     * @var list<PackageResponseLinesItem>
     */
    public array $lines;
    /**
     * The shipment properties of a package.
     *
     * @var Shipment
     */
    public Shipment $shipment;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'status' => 'status', 'physicalProperties' => 'physicalProperties', 'lines' => 'lines', 'shipment' => 'shipment'];
    }
}