<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentDefsTrackTrace
{
    /**
     * Unique ID of a Shipment.
     *
     * @var int
     */
    public int $shipmentId;
    /**
     * This enumeration represents the Carriers. The value is the Carrier id.
     *
     * @var RefTypesCarrier|int
     */
    public RefTypesCarrier|int $carrier;
    /**
     * @var string
     */
    public string $code;
    /**
     * @var string
     */
    public string $description;
    /**
     * Represents a date in ISO 8601 format and a time in ISO 8601 format, separated
     * by a space, so:
     * 
     * ```
     * YYYY-MM-DD hh:mm:ss.u
     * ```
     * 
     * Where:
     *   - `YYYY` represents a four-digit year, `0000` through `9999`
     *   - `MM` represents a zero-padded month of the year, `01` through `12`
     *   - `DD` represents a zero-padded day of that month, `01` through `31`
     * and:
     *   - `hh` represents a zero-padded hour, `00` through `24`
     *   - `mm` represents a zero-padded minute, `00` through `59`
     *   - `ss` (optional) represents a zero-padded second, `00` through `60`
     *     (where `60` is only used to denote an added leap second)
     *   - `.u` (optional) represents a fraction of a second, `.0` through `.999999+`
     * 
     *
     * @var string
     */
    public string $time;
    /**
     * @var string|null
     */
    public ?string $linkConsumerPortal;
    /**
     * @var string|null
     */
    public ?string $linkTracktrace;
    /**
     * @var list<ShipmentDefsExternalTrackTraceLink>
     */
    public array $partnerTracktraces;
    /**
     * @var ShipmentDefsShipmentRecipient
     */
    public ShipmentDefsShipmentRecipient $recipient;
    /**
     * @var ShipmentDefsShipmentSender
     */
    public ShipmentDefsShipmentSender $sender;
    /**
     * @var RefShipmentShipmentOptions
     */
    public RefShipmentShipmentOptions $options;
    /**
     * @var ShipmentDefsShipmentPickup|null
     */
    public ?ShipmentDefsShipmentPickup $pickup;
    /**
     * @var bool
     */
    public bool $delayed;
    /**
     * @var ShipmentDefsTrackingLocation
     */
    public ShipmentDefsTrackingLocation $location;
    /**
     * @var ShipmentDefsTrackTraceStatus
     */
    public ShipmentDefsTrackTraceStatus $status;
    /**
     * @var list<ShipmentDefsCarrierTrackTraceEvent>
     */
    public array $history;
    /**
     * @var string|null
     */
    public ?string $deliveryMomentType;
    /**
     * @var ShipmentDefsDeliveryMomentPropertiesTimeFrame|null
     */
    public ?ShipmentDefsDeliveryMomentPropertiesTimeFrame $deliveryMoment;
    /**
     * @var mixed|null
     */
    public $signature;
    /**
     * @var bool
     */
    public bool $returnable;
    /**
     * @var string|null
     */
    public ?string $lastMileCarrierName;
}