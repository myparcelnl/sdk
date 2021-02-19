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
     * @var string
     */
    protected $packageType;

    /**
     * @var \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractShipmentOptionsAdapter|null
     */
    protected $shipmentOptions;

    /**
     * @var string|null
     */
    protected $carrier;

    /**
     * @var \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractPickupLocationAdapter
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
     * @return string
     */
    public function getPackageType(): ?string
    {
        return $this->packageType;
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
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractPickupLocationAdapter|null
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
        if ($this->deliveryType === null) {
            return false;
        }

        return in_array(
            $this->deliveryType, [
                AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME,
                AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME,
            ]
        );
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
            "packageType"     => $this->getPackageType(),
            "isPickup"        => $this->isPickup(),
            "pickupLocation"  => $this->getPickupLocation() ? $this->getPickupLocation()->toArray() : null,
            "shipmentOptions" => $this->getShipmentOptions() ? $this->getShipmentOptions()->toArray() : null,
        ];
    }
}
