<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItem
{
    /**
     * The ID of the shop for which the capabilities are requested.
     *
     * @var int
     */
    public int $shopId;
    /**
     * The ID of the contract for which the capabilities are requested.
     *
     * @var int
     */
    public int $contractId;
    /**
     * Recipient data.
     *
     * @var CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemRecipient
     */
    public CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemRecipient $recipient;
    /**
     * Sender data.
     *
     * @var CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemSender
     */
    public CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemSender $sender;
    /**
     * Carrier ID definition for capabilities endpoints (v1)
     *
     * @var RefCapabilitiesSharedCarrier|int
     */
    public RefCapabilitiesSharedCarrier|int $carrierId;
    /**
     * @var float
     */
    public float $packageType;
    /**
     * Physical properties of the package.
     *
     * @var CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalProperties
     */
    public CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalProperties $physicalProperties;
    /**
     * Shipment options object. Empty objects indicate the option is requested/enabled.
     *
     * @var CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptions
     */
    public CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptions $options;
    /**
     * @var float
     */
    public float $deliveryType;
    /**
     * @var float
     */
    public float $shipmentType;
    /**
     * The shipment direction for which the capabilities are requested.
     *
     * @var CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemDirection|string
     */
    public CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemDirection|string $direction;
    /**
     * Pickup location criteria for filtering capabilities.
     *
     * @var CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickup
     */
    public CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickup $pickup;
}