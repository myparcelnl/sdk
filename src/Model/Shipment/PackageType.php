<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentPackageType;

/**
 * SDK-level package type references with readable names.
 */
final class PackageType
{
    public const PACKAGE = 'PACKAGE';
    public const MAILBOX = 'MAILBOX';
    public const LETTER = 'LETTER';
    public const DIGITAL_STAMP = 'DIGITAL_STAMP';
    public const PALLET = 'PALLET';
    public const SMALL_PACKAGE = 'SMALL_PACKAGE';
    public const ENVELOPE = 'ENVELOPE';

    /**
     * Map SDK-level package type to API id string.
     */
    public static function toId(string $packageType): string
    {
        $map = self::map();

        if (! isset($map[$packageType])) {
            throw new InvalidArgumentException("Unknown package type '{$packageType}'");
        }

        return $map[$packageType];
    }

    /**
     * Check if the given SDK-level package type is valid.
     */
    public static function isValid(string $packageType): bool
    {
        return isset(self::map()[$packageType]);
    }

    /**
     * Get all available SDK-level package types.
     *
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::PACKAGE,
            self::MAILBOX,
            self::LETTER,
            self::DIGITAL_STAMP,
            self::PALLET,
            self::SMALL_PACKAGE,
            self::ENVELOPE,
        ];
    }

    /**
     * Internal source of truth for name -> id mapping.
     *
     * @return array<string, string>
     */
    private static function map(): array
    {
        return [
            self::PACKAGE => RefShipmentPackageType::_1,
            self::MAILBOX => RefShipmentPackageType::_2,
            self::LETTER => RefShipmentPackageType::_3,
            self::DIGITAL_STAMP => RefShipmentPackageType::_4,
            self::PALLET => RefShipmentPackageType::_5,
            self::SMALL_PACKAGE => RefShipmentPackageType::_6,
            self::ENVELOPE => RefShipmentPackageType::_7,
        ];
    }
}
