<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Adapter\DeliveryOptions;

use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;

abstract class AbstractDeliveryOptionsAdapter
{
    public const DELIVERY_TYPE_MORNING       = 1;
    public const DELIVERY_TYPE_STANDARD      = 2;
    public const DELIVERY_TYPE_EVENING       = 3;
    public const DELIVERY_TYPE_PICKUP        = 4;
    public const DELIVERY_TYPE_EXPRESS       = 7;
    public const DELIVERY_TYPE_MORNING_NAME  = 'morning';
    public const DELIVERY_TYPE_STANDARD_NAME = 'standard';
    public const DELIVERY_TYPE_EVENING_NAME  = 'evening';
    public const DELIVERY_TYPE_PICKUP_NAME   = 'pickup';
    public const DELIVERY_TYPE_EXPRESS_NAME  = 'express';

    public const DELIVERY_TYPES_NAMES_IDS_MAP
        = [
            self::DELIVERY_TYPE_MORNING_NAME  => self::DELIVERY_TYPE_MORNING,
            self::DELIVERY_TYPE_STANDARD_NAME => self::DELIVERY_TYPE_STANDARD,
            self::DELIVERY_TYPE_EVENING_NAME  => self::DELIVERY_TYPE_EVENING,
            self::DELIVERY_TYPE_PICKUP_NAME   => self::DELIVERY_TYPE_PICKUP,
            self::DELIVERY_TYPE_EXPRESS_NAME  => self::DELIVERY_TYPE_EXPRESS,
        ];

    public const PACKAGE_TYPE_PACKAGE       = 1;
    public const PACKAGE_TYPE_MAILBOX       = 2;
    public const PACKAGE_TYPE_LETTER        = 3;
    public const PACKAGE_TYPE_DIGITAL_STAMP = 4;
    public const PACKAGE_TYPE_PACKAGE_SMALL = 6;

    public const PACKAGE_TYPE_PACKAGE_NAME       = 'package';
    public const PACKAGE_TYPE_MAILBOX_NAME       = 'mailbox';
    public const PACKAGE_TYPE_LETTER_NAME        = 'letter';
    public const PACKAGE_TYPE_DIGITAL_STAMP_NAME = 'digital_stamp';
    public const PACKAGE_TYPE_PACKAGE_SMALL_NAME = 'package_small';

    public const PACKAGE_TYPES_NAMES_IDS_MAP
        = [
            self::PACKAGE_TYPE_PACKAGE_NAME       => self::PACKAGE_TYPE_PACKAGE,
            self::PACKAGE_TYPE_MAILBOX_NAME       => self::PACKAGE_TYPE_MAILBOX,
            self::PACKAGE_TYPE_LETTER_NAME        => self::PACKAGE_TYPE_LETTER,
            self::PACKAGE_TYPE_DIGITAL_STAMP_NAME => self::PACKAGE_TYPE_DIGITAL_STAMP,
            self::PACKAGE_TYPE_PACKAGE_SMALL_NAME => self::PACKAGE_TYPE_PACKAGE_SMALL,
        ];

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
     * @var \MyParcelNL\Sdk\Adapter\DeliveryOptions\AbstractPickupLocationAdapter
     */
    protected $pickupLocation;

    /**
     * @var \MyParcelNL\Sdk\Adapter\DeliveryOptions\AbstractShipmentOptionsAdapter|null
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
        return self::DELIVERY_TYPES_NAMES_IDS_MAP[$this->deliveryType] ?? null;
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
        return self::PACKAGE_TYPES_NAMES_IDS_MAP[$this->packageType] ?? null;
    }

    /**
     * @return \MyParcelNL\Sdk\Adapter\DeliveryOptions\AbstractPickupLocationAdapter|null
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

        return self::DELIVERY_TYPE_PICKUP_NAME == $this->deliveryType;
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
