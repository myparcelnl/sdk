<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShippablePackage implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
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
    /**
     * @var ShippablePackageShipment
     */
    public ShippablePackageShipment $shipment;
    public function definedProperties(): array
    {
        return ['customsDeclaration' => 'customsDeclaration', 'options' => 'options', 'physicalProperties' => 'physicalProperties', 'shipment' => 'shipment'];
    }
}