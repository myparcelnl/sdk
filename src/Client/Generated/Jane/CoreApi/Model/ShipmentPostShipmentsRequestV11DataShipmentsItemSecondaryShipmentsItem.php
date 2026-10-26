<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem
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
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipient
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipient $recipient;
    /**
     * @var list<float>
     */
    public array $recipients;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalProperties
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalProperties $physicalProperties;
    /**
     * @var RefShipmentShipmentOptions
     */
    public RefShipmentShipmentOptions $options;
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
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPoint|null
     */
    public ?ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPoint $dropOffPoint;
    /**
     * @var string
     */
    public string $note;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemStatus|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemStatus|int $status;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDelivered|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDelivered|int $delivered;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettings
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemHidden|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemHidden|int $hidden;
    /**
     * @var list<ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItem>
     */
    public array $shippedItems;
}