<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPutShipmentsRequestV11DataShipmentsItem
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
     * @var int
     */
    public int $contractId;
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
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemDelivered|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemDelivered|int $delivered;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemRecipient
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemRecipient $recipient;
    /**
     * @var RefShipmentSender
     */
    public RefShipmentSender $sender;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemOptions
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemOptions $options;
    /**
     * @var RefShipmentCustomsDeclaration
     */
    public RefShipmentCustomsDeclaration $customsDeclaration;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemPhysicalProperties
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemPhysicalProperties $physicalProperties;
    /**
     * @var RefShipmentPickup|null
     */
    public ?RefShipmentPickup $pickup;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPoint|null
     */
    public ?ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPoint $dropOffPoint;
    /**
     * @var string
     */
    public string $note;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettings
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettings $generalSettings;
    /**
     * @var int|null
     */
    public ?int $multiColloMainShipmentId;
    /**
     * @var list<ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem>
     */
    public array $secondaryShipments;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemCollectionContact
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemCollectionContact $collectionContact;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemHidden|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemHidden|int $hidden;
    /**
     * @var list<ShipmentPutShipmentsRequestV11DataShipmentsItemShippedItemsItem>
     */
    public array $shippedItems;
}