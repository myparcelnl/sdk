<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier;

/**
 * SDK-level carrier references with readable names.
 */
final class Carrier
{
    public const POSTNL = 'POSTNL';
    public const BPOST = 'BPOST';
    public const CHEAP_CARGO = 'CHEAP_CARGO';
    public const DPD = 'DPD';
    public const DHL_FOR_YOU = 'DHL_FOR_YOU';
    public const DHL_PARCEL_CONNECT = 'DHL_PARCEL_CONNECT';
    public const DHL_EUROPLUS = 'DHL_EUROPLUS';
    public const UPS_STANDARD = 'UPS_STANDARD';
    public const UPS_EXPRESS_SAVER = 'UPS_EXPRESS_SAVER';
    public const GLS = 'GLS';
    public const BRT = 'BRT';
    public const TRUNKRS = 'TRUNKRS';
    public const INPOST = 'INPOST';
    public const POSTE_ITALIANE = 'POSTE_ITALIANE';

    /**
     * Map SDK-level carrier name to API carrier reference.
     *
     * @return RefTypesCarrier
     */
    public static function toApiRef(string $carrier): int
    {
        $map = self::map();

        if (! isset($map[$carrier])) {
            throw new InvalidArgumentException("Unknown carrier '{$carrier}'");
        }

        return $map[$carrier];
    }

    /**
     * Map SDK-level carrier name to numeric API id.
     */
    public static function toId(string $carrier): int
    {
        return (int) self::toApiRef($carrier);
    }

    /**
     * Check if the given SDK-level carrier name is valid.
     */
    public static function isValid(string $carrier): bool
    {
        return isset(self::map()[$carrier]);
    }

    /**
     * Get all available SDK-level carrier names.
     *
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::POSTNL,
            self::BPOST,
            self::CHEAP_CARGO,
            self::DPD,
            self::DHL_FOR_YOU,
            self::DHL_PARCEL_CONNECT,
            self::DHL_EUROPLUS,
            self::UPS_STANDARD,
            self::UPS_EXPRESS_SAVER,
            self::GLS,
            self::BRT,
            self::TRUNKRS,
            self::INPOST,
            self::POSTE_ITALIANE,
        ];
    }

    /**
     * Internal source of truth for name -> id mapping.
     *
     * @return array<string, int>
     */
    private static function map(): array
    {
        // @TODO: Temporary map for compatibility, should be dynamic in the future
        return [
            self::POSTNL => RefTypesCarrier::POSTNL,
            self::BPOST => RefTypesCarrier::BPOST,
            self::CHEAP_CARGO => RefTypesCarrier::CHEAP_CARGO,
            self::DPD => RefTypesCarrier::DPD,
            self::DHL_FOR_YOU => RefTypesCarrier::DHL_FOR_YOU,
            self::DHL_PARCEL_CONNECT => RefTypesCarrier::DHL_PARCEL_CONNECT,
            self::DHL_EUROPLUS => RefTypesCarrier::DHL_EUROPLUS,
            self::UPS_STANDARD => RefTypesCarrier::UPS_STANDARD,
            self::UPS_EXPRESS_SAVER => RefTypesCarrier::UPS_EXPRESS_SAVER,
            self::GLS => RefTypesCarrier::GLS,
            self::BRT => RefTypesCarrier::BRT,
            self::TRUNKRS => RefTypesCarrier::TRUNKRS,
            self::INPOST => RefTypesCarrier::INPOST,
            self::POSTE_ITALIANE => RefTypesCarrier::POSTE_ITALIANE,
        ];
    }
}
