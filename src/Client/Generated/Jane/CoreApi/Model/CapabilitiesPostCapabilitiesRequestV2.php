<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class CapabilitiesPostCapabilitiesRequestV2
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
     * @var CapabilitiesPostCapabilitiesRequestV2Recipient
     */
    public CapabilitiesPostCapabilitiesRequestV2Recipient $recipient;
    /**
     * Sender data.
     *
     * @var CapabilitiesPostCapabilitiesRequestV2Sender
     */
    public CapabilitiesPostCapabilitiesRequestV2Sender $sender;
    /**
     * Carrier string definition for capabilities endpoints (v2)
     *
     * @var RefCapabilitiesSharedCarrierV2|string
     */
    public RefCapabilitiesSharedCarrierV2|string $carrier;
    /**
     * Package Type.
     *
     * @var RefShipmentPackageTypeV2|string
     */
    public RefShipmentPackageTypeV2|string $packageType;
    /**
     * Physical properties of the package.
     *
     * @var CapabilitiesPostCapabilitiesRequestV2PhysicalProperties
     */
    public CapabilitiesPostCapabilitiesRequestV2PhysicalProperties $physicalProperties;
    /**
     * Shipment options object. Empty objects indicate the option is requested/enabled.
     *
     * @var CapabilitiesPostCapabilitiesRequestV2Options
     */
    public CapabilitiesPostCapabilitiesRequestV2Options $options;
    /**
     * @var CapabilitiesPostCapabilitiesRequestV2DeliveryType|string
     */
    public CapabilitiesPostCapabilitiesRequestV2DeliveryType|string $deliveryType;
    /**
     * The shipment direction for which the capabilities are requested.
     *
     * @var CapabilitiesPostCapabilitiesRequestV2Direction|string
     */
    public CapabilitiesPostCapabilitiesRequestV2Direction|string $direction;
    /**
     * Pickup location criteria for filtering capabilities.
     *
     * @var CapabilitiesPostCapabilitiesRequestV2Pickup
     */
    public CapabilitiesPostCapabilitiesRequestV2Pickup $pickup;
}