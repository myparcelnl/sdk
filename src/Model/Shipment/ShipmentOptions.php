<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentShipmentOptions;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesPriceEuro;
use MyParcelNL\Sdk\Model\Shipment\Mapping\DeliveryTypeApiMapping;

/**
 * SDK wrapper around generated shipment options.
 * Keeps package_type normalized to integer ids and accepts v2 enum names
 * for SDK convenience.
 */
class ShipmentOptions extends RefShipmentShipmentOptions
{
    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }

    /**
     * Hydrate shipment options from the legacy Order v1 shipment response shape.
     *
     * Order v1 nests the shared shipment options contract under `shipment_options`
     * and historically used `date` where the shared shipment contract uses
     * `delivery_date`.
     *
     * @param array<string, mixed> $shipmentData
     */
    public static function fromOrderResponse(array $shipmentData): self
    {
        $options = $shipmentData['shipment_options'] ?? $shipmentData['options'] ?? [];

        return new self(array_filter([
            'delivery_date'     => $options['delivery_date'] ?? $options['date'] ?? null,
            'delivery_type'     => $options['delivery_type'] ?? null,
            'package_type'      => $options['package_type'] ?? null,
            'signature'         => self::intBooleanOrNull($options['signature'] ?? null),
            'collect'           => self::intBooleanOrNull($options['collect'] ?? null),
            'receipt_code'      => self::intBooleanOrNull($options['receipt_code'] ?? null),
            'only_recipient'    => self::intBooleanOrNull($options['only_recipient'] ?? null),
            'age_check'         => self::intBooleanOrNull($options['age_check'] ?? null),
            'large_format'      => self::intBooleanOrNull($options['large_format'] ?? null),
            'return'            => self::intBooleanOrNull($options['return'] ?? null),
            'priority_delivery' => self::intBooleanOrNull($options['priority_delivery'] ?? null),
            'label_description' => $options['label_description'] ?? null,
            'insurance'         => $options['insurance'] ?? null,
        ], static function ($value): bool {
            return null !== $value;
        }));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = json_decode(json_encode($this), true);

        return is_array($data) ? $data : [];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayWithoutNull(): array
    {
        return array_filter($this->toArray(), static function ($value): bool {
            return null !== $value;
        });
    }

    /**
     * Lazily normalize delivery_type after generated model hydration.
     *
     * @return int|string|null
     */
    public function getDeliveryType()
    {
        $deliveryType = parent::getDeliveryType();

        if (is_string($deliveryType)) {
            $this->setDeliveryType($deliveryType);

            return parent::getDeliveryType();
        }

        return $deliveryType;
    }

    /**
     * Lazily normalize insurance after generated model hydration.
     *
     * @return RefTypesPriceEuro|int|null
     */
    public function getInsurance()
    {
        $insurance = parent::getInsurance();

        if (is_array($insurance)) {
            $this->setInsurance(new RefTypesPriceEuro($insurance));

            return parent::getInsurance();
        }

        return $insurance;
    }

    /**
     * Lazily normalize package_type after generated model hydration.
     *
     * @return int|string|null
     */
    public function getPackageType()
    {
        $packageType = parent::getPackageType();

        if (is_string($packageType)) {
            $this->setPackageType($packageType);

            return parent::getPackageType();
        }

        return $packageType;
    }

    /**
     * Ensure package_type is serialized as integer.
     *
     * @return array<string, string>
     */
    public static function openAPITypes()
    {
        $types = parent::openAPITypes();
        $types['package_type'] = 'int';

        return $types;
    }

    /**
     * @param int|string|null $packageType
     * @return self
     */
    public function setPackageType($packageType)
    {
        if (is_string($packageType)) {
            if (ctype_digit($packageType)) {
                $packageType = (int) $packageType;
            } elseif (PackageType::isValid($packageType)) {
                $packageType = PackageType::toId($packageType);
            } else {
                throw new InvalidArgumentException("Unknown package type '{$packageType}'");
            }
        }

        return parent::setPackageType($packageType);
    }

    /**
     * @param int|string|null $deliveryType
     * @return self
     */
    public function setDeliveryType($deliveryType)
    {
        if (is_string($deliveryType)) {
            if (ctype_digit($deliveryType)) {
                $deliveryType = (int) $deliveryType;
            } else {
                $mapping = new DeliveryTypeApiMapping();
                $deliveryType = $mapping->enumToId($deliveryType);
            }
        }

        return parent::setDeliveryType($deliveryType);
    }

    private static function intBooleanOrNull($value): ?int
    {
        return null === $value ? null : (int) (bool) $value;
    }
}
