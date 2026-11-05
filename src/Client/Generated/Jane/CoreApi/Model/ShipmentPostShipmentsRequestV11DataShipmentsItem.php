<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPostShipmentsRequestV11DataShipmentsItem
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
     * @var int
     */
    public int $contractId;
    /**
     * @var int|string
     */
    public $referenceIdentifier;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient $recipient;
    /**
     * @var RefShipmentSender
     */
    public RefShipmentSender $sender;
    /**
     * @var list<float>
     */
    public array $recipients;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalProperties
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalProperties $physicalProperties;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemOptions
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemOptions $options;
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
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPoint|null
     */
    public ?ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPoint $dropOffPoint;
    /**
     * @var string
     */
    public string $note;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemStatus|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemStatus|int $status;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemDelivered|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemDelivered|int $delivered;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var list<ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem>
     */
    public array $secondaryShipments;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact $collectionContact;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemHidden|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemHidden|int $hidden;
    /**
     * @var list<ShipmentPostShipmentsRequestV11DataShipmentsItemShippedItemsItem>
     */
    public array $shippedItems;
}