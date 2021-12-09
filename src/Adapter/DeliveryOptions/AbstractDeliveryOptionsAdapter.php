<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use MyParcelNL\Sdk\src\Concerns\Initializable\HasCarrier;
use MyParcelNL\Sdk\src\Concerns\Initializable\HasDeliveryType;
use MyParcelNL\Sdk\src\Concerns\Initializable\HasPackageType;
use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

abstract class AbstractDeliveryOptionsAdapter
{
    /**
     * @var string|null
     */
    protected $date;

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
    public function getDate(): ?string
    {
        return $this->date;
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
        if (null === $this->deliveryType) {
            return false;
        }

        return in_array(
            $this->getDeliveryType(),
            [
                AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME,
                AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME,
            ],
            true
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'carrier'         => $this->getCarrier(),
            'date'            => $this->getDate(),
            'deliveryType'    => $this->getDeliveryType(),
            'packageType'     => $this->getPackageTypeName(),
            'isPickup'        => $this->isPickup(),
            'pickupLocation'  => $this->getPickupLocation() ? $this->getPickupLocation()
                ->toArray() : null,
            'shipmentOptions' => $this->getShipmentOptions() ? $this->getShipmentOptions()
                ->toArray() : null,
        ];
    }
}
