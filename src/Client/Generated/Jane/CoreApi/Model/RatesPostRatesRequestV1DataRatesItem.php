<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RatesPostRatesRequestV1DataRatesItem
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
     * @var RatesPostRatesRequestV1DataRatesItemRecipient
     */
    public RatesPostRatesRequestV1DataRatesItemRecipient $recipient;
    /**
     * Sender data.
     *
     * @var RatesPostRatesRequestV1DataRatesItemSender
     */
    public RatesPostRatesRequestV1DataRatesItemSender $sender;
    /**
     * @var ShipmentDefsShipmentPickup|null
     */
    public ?ShipmentDefsShipmentPickup $pickup;
    /**
     * Carrier ID definition for capabilities endpoints (v1)
     *
     * @var RefCapabilitiesSharedCarrier|int
     */
    public RefCapabilitiesSharedCarrier|int $carrierId;
    /**
     * Package Type.
     *
     * @var RefShipmentPackageType|int
     */
    public RefShipmentPackageType|int $packageType;
    /**
     * Physical properties of the package.
     *
     * @var RatesPostRatesRequestV1DataRatesItemPhysicalProperties
     */
    public RatesPostRatesRequestV1DataRatesItemPhysicalProperties $physicalProperties;
    /**
     * Shipment options object. Empty objects indicate the option is requested/enabled.
     *
     * @var RatesPostRatesRequestV1DataRatesItemOptions
     */
    public RatesPostRatesRequestV1DataRatesItemOptions $options;
    /**
     * @var float
     */
    public float $deliveryType;
    /**
     * @var float
     */
    public float $shipmentType;
    /**
     * The shipment direction for which the rates are requested.
     *
     * @var RatesPostRatesRequestV1DataRatesItemDirection|string
     */
    public RatesPostRatesRequestV1DataRatesItemDirection|string $direction;
    /**
     * The number of collo for which the rates are requested.
     *
     * @var int
     */
    public int $collo;
}