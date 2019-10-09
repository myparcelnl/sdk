<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\DeliveryOptions;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;

/**
 * Class DeliveryOptions
 *
 * @package MyParcelNL\Sdk\src\Model\DeliveryOptions
 */
class DeliveryOptions
{
    /**
     * Default values to use if there is no input.
     */
    public const DEFAULTS = [
        "carrier"         => BpostConsignment::CARRIER_NAME,
        "deliveryType"    => "standard",
        "date"            => "",
        "shipmentOptions" => [],
        "isPickup"        => false,
    ];

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $deliveryType;

    /**
     * @var object|null
     */
    private $shipmentOptions;

    /**
     * @var bool
     */
    private $isPickup;

    /**
     * @var string
     */
    private $carrier;

    /**
     * @var PickupLocation
     */
    private $pickupLocation;

    /**
     * DeliveryOptions constructor.
     *
     * @param array $deliveryOptions
     *
     * @throws Exception
     */
    public function __construct($deliveryOptions = [])
    {
        if (! count($deliveryOptions)) {
            $deliveryOptions = self::DEFAULTS;
        }

        if (array_key_exists("carrier", $deliveryOptions)) {
            $carrier = $deliveryOptions["carrier"];
        }

        $this->carrier         = $carrier ?? BpostConsignment::CARRIER_NAME;
        $this->date            = $deliveryOptions["date"];
        $this->deliveryType    = $deliveryOptions["deliveryType"];
        $this->isPickup        = $deliveryOptions["isPickup"] ?? false;
        $this->shipmentOptions = new ShipmentOptions($deliveryOptions["shipmentOptions"] ?? []);

        if ($this->isPickup()) {
            $this->pickupLocation = new PickupLocation($deliveryOptions["pickupLocation"]);
        }
    }

    /**
     * @return string
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    /**
     * @return ShipmentOptions
     */
    public function getShipmentOptions(): ShipmentOptions
    {
        return $this->shipmentOptions;
    }

    /**
     * @return string
     */
    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    /**
     * @return PickupLocation
     */
    public function getPickupLocation(): ?PickupLocation
    {
        return $this->pickupLocation;
    }

    /**
     * @return bool
     */
    public function isPickup(): bool
    {
        return $this->isPickup;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "carrier"         => $this->getCarrier(),
            "date"            => $this->getDate(),
            "deliveryType"    => $this->getDeliveryType(),
            "isPickup"        => $this->isPickup(),
            "pickupLocation"  => $this->getPickupLocation(),
            "shipmentOptions" => $this->getShipmentOptions()->toArray(),
        ];
    }
}
