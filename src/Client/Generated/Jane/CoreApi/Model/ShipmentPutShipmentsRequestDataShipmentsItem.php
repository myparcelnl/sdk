<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPutShipmentsRequestDataShipmentsItem
{
    /**
     * @var int
     */
    public int $id;
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
     * This enumeration represents the Carriers. The value is the Carrier id.
     *
     * @var RefTypesCarrier|int
     */
    public RefTypesCarrier|int $carrier;
    /**
     * @var int
     */
    public int $status;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemDelivered|int
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemDelivered|int $delivered;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemRecipient
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemRecipient $recipient;
    /**
     * @var RefShipmentSender
     */
    public RefShipmentSender $sender;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemOptions
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemOptions $options;
    /**
     * @var RefShipmentCustomsDeclaration
     */
    public RefShipmentCustomsDeclaration $customsDeclaration;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemPhysicalProperties
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemPhysicalProperties $physicalProperties;
    /**
     * @var RefShipmentPickup|null
     */
    public ?RefShipmentPickup $pickup;
    /**
     * @var string
     */
    public string $note;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettings
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemHidden|int
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemHidden|int $hidden;
}