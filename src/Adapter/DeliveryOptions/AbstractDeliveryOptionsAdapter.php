<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

abstract class AbstractDeliveryOptionsAdapter
{
    /**
     * @var string
     */
    protected $date;

    /**
     * @var string
     */
    protected $deliveryType;

    /**
     * @var AbstractShipmentOptionsAdapter|null
     */
    protected $shipmentOptions;

    /**
     * @var string
     */
    protected $carrier;

    /**
     * @var bool
     */
    protected $pickup;

    /**
     * @var AbstractPickupLocationAdapter
     */
    protected $pickupLocation;

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
     * @return int|null
     */
    public function getDeliveryTypeId(): ?int
    {
        if ($this->deliveryType === null) {
            return null;
        }

        return AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP[$this->deliveryType];
    }

    /**
     * @return AbstractShipmentOptionsAdapter|null
     */
    public function getShipmentOptions(): ?AbstractShipmentOptionsAdapter
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
     * @return AbstractPickupLocationAdapter|null
     */
    public function getPickupLocation(): ?AbstractPickupLocationAdapter
    {
        return $this->pickupLocation;
    }

    /**
     * @return bool
     */
    public function isPickup(): bool
    {
        return $this->pickup;
    }

    /**
     * @param string $carrier
     *
     * @return self
     */
    public function setCarrier(string $carrier): self
    {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @param AbstractShipmentOptionsAdapter $shipmentOptions
     *
     * @return self
     */
    public function setShipmentOptions(AbstractShipmentOptionsAdapter $shipmentOptions): self
    {
        $this->shipmentOptions = $shipmentOptions;
        return $this;
    }

    /**
     * @param string $date
     *
     * @return AbstractDeliveryOptionsAdapter
     */
    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param string $deliveryType
     *
     * @return AbstractDeliveryOptionsAdapter
     */
    public function setDeliveryType(string $deliveryType): self
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    /**
     * @param bool $pickup
     *
     * @return AbstractDeliveryOptionsAdapter
     */
    public function setPickup(bool $pickup): self
    {
        $this->pickup = $pickup;
        return $this;
    }

    /**
     * @param AbstractPickupLocationAdapter $pickupLocation
     *
     * @return AbstractDeliveryOptionsAdapter
     */
    public function setPickupLocation(AbstractPickupLocationAdapter $pickupLocation): self
    {
        $this->pickupLocation = $pickupLocation;
        return $this;
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
