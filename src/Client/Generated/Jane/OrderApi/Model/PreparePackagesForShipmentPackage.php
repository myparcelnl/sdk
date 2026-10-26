<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class PreparePackagesForShipmentPackage implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the package.
     *
     * @var string
     */
    public string $id;
    /**
     * The official declaration of goods for customs clearance purposes during international shipment.
     *
     * @var CustomsDeclaration
     */
    public CustomsDeclaration $customsDeclaration;
    /**
     * Options applicable to individual packages of a shipment.
     *
     * @var ShipmentOptionsPackage
     */
    public ShipmentOptionsPackage $options;
    /**
     * The physical dimensions.
     *
     * @var PhysicalPropertiesWeightRequired
     */
    public PhysicalPropertiesWeightRequired $physicalProperties;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'customsDeclaration' => 'customsDeclaration', 'options' => 'options', 'physicalProperties' => 'physicalProperties'];
    }
}