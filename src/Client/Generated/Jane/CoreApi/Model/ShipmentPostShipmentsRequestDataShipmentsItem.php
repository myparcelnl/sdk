<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPostShipmentsRequestDataShipmentsItem
{
    /**
     * @var int
     */
    public int $accountId;
    /**
     * @var int
     */
    public int $shopId;
    /**
     * @var int|string
     */
    public $referenceIdentifier;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemRecipient
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemRecipient $recipient;
    /**
     * @var RefShipmentSender
     */
    public RefShipmentSender $sender;
    /**
     * @var list<float>
     */
    public array $recipients;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemPhysicalProperties
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemPhysicalProperties $physicalProperties;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemOptions
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemOptions $options;
    /**
     * @var RefShipmentCustomsDeclaration
     */
    public RefShipmentCustomsDeclaration $customsDeclaration;
    /**
     * This enumeration represents the Carriers. The value is the Carrier id.
     *
     * @var RefTypesCarrier|int
     */
    public RefTypesCarrier|int $carrier;
    /**
     * @var RefShipmentPickup|null
     */
    public ?RefShipmentPickup $pickup;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemMultiColli|null
     */
    public ?ShipmentPostShipmentsRequestDataShipmentsItemMultiColli $multiColli;
    /**
     * @var string
     */
    public string $note;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemStatus|int
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemStatus|int $status;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemDelivered|int
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemDelivered|int $delivered;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettings
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemHidden|int
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemHidden|int $hidden;
}