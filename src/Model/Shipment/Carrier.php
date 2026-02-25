<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use MyParcelNL\Sdk\Model\Shipment\Mapping\CarrierApiMapping;

/**
 * Thin translator between public carrier enum values and shipment v1 ids.
 *
 * Public values come from generated v2 refs.
 * Request payload values for shipment create remain generated v1 ids.
 */
final class Carrier
{
    /**
     * @return string RefTypesCarrier value.
     */
    public static function toApiRef(string $carrier): int
    {
        return self::mapping()->enumToApiRef($carrier);
    }

    public static function toId(string $carrier): int
    {
        return self::mapping()->enumToId($carrier);
    }

    public static function fromId(int $id): string
    {
        return self::mapping()->idToEnum($id);
    }

    public static function isValid(string $carrier): bool
    {
        return self::mapping()->isValid($carrier);
    }

    /**
     * @return string[] List of generated v2 carrier enum values.
     */
    public static function all(): array
    {
        return self::mapping()->all();
    }

    private static function mapping(): CarrierApiMapping
    {
        static $mapping;

        if (null === $mapping) {
            $mapping = new CarrierApiMapping();
        }

        return $mapping;
    }
}
