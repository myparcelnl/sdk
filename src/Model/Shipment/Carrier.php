<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use MyParcelNL\Sdk\Model\Shipment\Mapping\CarrierApiMapping;

/**
 * Thin translator between public carrier enum values and shipment v1 ids.
 *
 * Public values come from generated v2 refs.
 * Request payload values for shipment create remain generated v1 ids.
 *
 * @todo Introduce a shared shipment domain model interface if this class gains stateful attributes later.
 */
final class Carrier
{
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
     * @return array<string, string> key = v2 enum value, value = v1 shipment id.
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
