<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentPostReturnShipmentsRequestDataReturnShipmentsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var int
     */
    public int $accountId;
    /**
     * @var int
     */
    public int $shopId;
    /**
     * @var int
     */
    public int $contractId;
    /**
     * @var int
     */
    public int $parent;
    /**
     * @var int|string
     */
    public $referenceIdentifier;
    /**
     * @var RefTypesCarrierReturns|int
     */
    public RefTypesCarrierReturns|int $carrier;
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $name;
    /**
     * @var ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemSender
     */
    public ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemSender $sender;
    /**
     * @var ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemPhysicalProperties
     */
    public ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemPhysicalProperties $physicalProperties;
    /**
     * @var RefShipmentCustomsDeclaration
     */
    public RefShipmentCustomsDeclaration $customsDeclaration;
    /**
     * @var ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings
     */
    public ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var RefShipmentShipmentOptionsReturnsOptions
     */
    public RefShipmentShipmentOptionsReturnsOptions $options;
    public function definedProperties(): array
    {
        return ['accountId' => 'account_id', 'shopId' => 'shop_id', 'contractId' => 'contract_id', 'parent' => 'parent', 'referenceIdentifier' => 'reference_identifier', 'carrier' => 'carrier', 'email' => 'email', 'name' => 'name', 'sender' => 'sender', 'physicalProperties' => 'physical_properties', 'customsDeclaration' => 'customs_declaration', 'generalSettings' => 'general_settings', 'options' => 'options'];
    }
}