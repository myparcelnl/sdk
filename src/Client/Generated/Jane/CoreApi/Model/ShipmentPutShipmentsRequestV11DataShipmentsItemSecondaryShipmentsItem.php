<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem
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
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipient
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipient $recipient;
    /**
     * @var list<float>
     */
    public array $recipients;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalProperties
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalProperties $physicalProperties;
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
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPoint|null
     */
    public ?ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPoint $dropOffPoint;
    /**
     * @var string
     */
    public string $note;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemStatus|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemStatus|int $status;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDelivered|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDelivered|int $delivered;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettings
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var int|null
     */
    public ?int $multiColloMainShipmentId;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemHidden|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemHidden|int $hidden;
    /**
     * @var list<ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItem>
     */
    public array $shippedItems;
}