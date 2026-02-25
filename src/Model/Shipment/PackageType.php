<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use MyParcelNL\Sdk\Model\Shipment\Mapping\PackageTypeApiMapping;

/**
 * Thin translator between public package type enum values and shipment v1 ids.
 *
 * Public values come from generated v2 refs.
 * Request payload values for shipment create remain generated v1 ids.
 */
final class PackageType
{
    /**
     * @return string RefShipmentPackageType value.
     */
    public static function toApiRef(string $packageType): int
    {
        return self::mapping()->enumToApiRef($packageType);
    }

    public static function toId(string $packageType): int
    {
        return self::mapping()->enumToId($packageType);
    }

    public static function fromId(int $id): string
    {
        return self::mapping()->idToEnum($id);
    }

    public static function isValid(string $packageType): bool
    {
        return self::mapping()->isValid($packageType);
    }

    /**
     * @return string[] List of generated v2 package type enum values.
     */
    public static function all(): array
    {
        return self::mapping()->all();
    }

    private static function mapping(): PackageTypeApiMapping
    {
        static $mapping;

        if (null === $mapping) {
            $mapping = new PackageTypeApiMapping();
        }

        return $mapping;
    }
}
