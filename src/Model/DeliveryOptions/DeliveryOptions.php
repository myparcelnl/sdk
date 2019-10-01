<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\DeliveryOptions;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;

class DeliveryOptions
{
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
    public function __construct(array $deliveryOptions)
    {
        if (array_key_exists("carrier", $deliveryOptions)) {
            $carrier = $deliveryOptions["carrier"];
        }

        $this->deliveryType    = $deliveryOptions["deliveryType"];
        $this->date            = $deliveryOptions["date"];
        $this->shipmentOptions = new ShipmentOptions($deliveryOptions["shipmentOptions"]);
        $this->isPickup        = $deliveryOptions["isPickup"];
        $this->carrier         = $carrier ?? BpostConsignment::CARRIER_NAME;

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
}
