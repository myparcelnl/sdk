<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem implements AdditionalPropertiesInterface
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
     * @var string
     */
    public string $cc;
    /**
     * @var ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemSender
     */
    public ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemSender $sender;
    /**
     * @var ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemPhysicalProperties
     */
    public ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemPhysicalProperties $physicalProperties;
    /**
     * @var RefShipmentCustomsDeclaration
     */
    public RefShipmentCustomsDeclaration $customsDeclaration;
    /**
     * @var ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings
     */
    public ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var RefShipmentShipmentOptionsReturnsOptions
     */
    public RefShipmentShipmentOptionsReturnsOptions $options;
    public function definedProperties(): array
    {
        return ['accountId' => 'account_id', 'shopId' => 'shop_id', 'carrier' => 'carrier', 'email' => 'email', 'name' => 'name', 'cc' => 'cc', 'sender' => 'sender', 'physicalProperties' => 'physical_properties', 'customsDeclaration' => 'customs_declaration', 'generalSettings' => 'general_settings', 'options' => 'options'];
    }
}