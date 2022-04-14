<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

abstract class AbstractDeliveryOptionsAdapter
{
    /**
     * @var string|null
     */
    protected $carrier;

    /**
     * @var string|null
     */
    protected $date;

    /**
     * @var string|null
     */
    protected $deliveryType;

    /**
     * @var string|null
     */
    protected $packageType;

    /**
     * @var \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractPickupLocationAdapter
     */
    protected $pickupLocation;

    /**
     * @var \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractShipmentOptionsAdapter|null
     */
    protected $shipmentOptions;

    /**
     * @return string
     */
    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    /**
     * @return int|null
     * @throws \Exception
     */
    public function getCarrierId(): ?int
    {
        if (! $this->carrier) {
            return null;
        }

        return CarrierFactory::create($this->carrier)->getId();
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
     * @return int|null
     */
    public function getDeliveryTypeId(): ?int
    {
        return AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP[$this->deliveryType] ?? null;
    }

    /**
     * @return string
     */
    public function getPackageType(): ?string
    {
        return $this->packageType;
    }

    /**
     * @return int
     */
    public function getPackageTypeId(): ?int
    {
        return AbstractConsignment::PACKAGE_TYPES_NAMES_IDS_MAP[$this->packageType] ?? null;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractPickupLocationAdapter|null
     */
    public function getPickupLocation(): ?AbstractPickupLocationAdapter
    {
        return $this->pickupLocation;
    }

    /**
     * @return AbstractShipmentOptionsAdapter|null
     */
    public function getShipmentOptions(): ?AbstractShipmentOptionsAdapter
    {
        return $this->shipmentOptions;
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
            $this->deliveryType,
            [
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
