<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RatesPostRatesRequestV2
{
    /**
     * The ID of the shop for which the rates are requested.
     *
     * @var int
     */
    public int $shopId;
    /**
     * The ID of the contract for which the rates are requested.
     *
     * @var int
     */
    public int $contractId;
    /**
     * Recipient data.
     *
     * @var RatesPostRatesRequestV2Recipient
     */
    public RatesPostRatesRequestV2Recipient $recipient;
    /**
     * Sender data.
     *
     * @var RatesPostRatesRequestV2Sender
     */
    public RatesPostRatesRequestV2Sender $sender;
    /**
     * Pickup location data.
     *
     * @var RefShipmentPickupV2
     */
    public RefShipmentPickupV2 $pickup;
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
     * @var RatesPostRatesRequestV2PhysicalProperties
     */
    public RatesPostRatesRequestV2PhysicalProperties $physicalProperties;
    /**
     * Shipment options object. Empty objects indicate the option is requested/enabled.
     *
     * @var RatesPostRatesRequestV2Options
     */
    public RatesPostRatesRequestV2Options $options;
    /**
     * @var RatesPostRatesRequestV2DeliveryType|string
     */
    public RatesPostRatesRequestV2DeliveryType|string $deliveryType;
    /**
     * The shipment direction for which the rates are requested.
     *
     * @var RatesPostRatesRequestV2Direction|string
     */
    public RatesPostRatesRequestV2Direction|string $direction;
    /**
     * The number of collo for which the rates are requested.
     *
     * @var int
     */
    public int $collo;
}