<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities\Enum;

final class Carrier
{
    public const POSTNL               = 'POSTNL';
    public const BPOST                = 'BPOST';
    public const DPD                  = 'DPD';
    public const GLS                  = 'GLS';
    public const DHL_FOR_YOU          = 'DHL_FOR_YOU';
    public const DHL_PARCEL_CONNECT   = 'DHL_PARCEL_CONNECT';
    public const DHL_EUROPLUS         = 'DHL_EUROPLUS';
    public const UPS_STANDARD         = 'UPS_STANDARD';
    public const UPS_EXPRESS_SAVER    = 'UPS_EXPRESS_SAVER';
    
    // Additional carriers from Core API v2 that may not have SDK implementations yet
    public const BOL                  = 'BOL';
    public const BRT                  = 'BRT';
    public const CHEAP_CARGO          = 'CHEAP_CARGO';
    public const TRUNKRS              = 'TRUNKRS';

    /**
     * Get all available carriers.
     * 
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::POSTNL,
            self::BPOST,
            self::DPD,
            self::GLS,
            self::DHL_FOR_YOU,
            self::DHL_PARCEL_CONNECT,
            self::DHL_EUROPLUS,
            self::UPS_STANDARD,
            self::UPS_EXPRESS_SAVER,
            self::BOL,
            self::BRT,
            self::CHEAP_CARGO,
            self::TRUNKRS,
        ];
    }

    /**
     * Get commonly used carriers (those with SDK implementations).
     * 
     * @return string[]
     */
    public static function common(): array
    {
        return [
            self::POSTNL,
            self::BPOST,
            self::DPD,
            self::GLS,
            self::DHL_FOR_YOU,
            self::DHL_PARCEL_CONNECT,
            self::DHL_EUROPLUS,
            self::UPS_STANDARD,
            self::UPS_EXPRESS_SAVER,
        ];
    }

    /**
     * Normalize variants like "post-nl", "PostNL", "dpd", "B-Post", "dhl-for-you", "ups_standard" → "POSTNL", "DPD", "DHL_FOR_YOU", "UPS_STANDARD", etc.
     * Unknown carriers will be uppercased and stripped from symbols (best effort).
     */
    public static function normalize(?string $value): ?string
    {
        if (null === $value || '' === $value) {
            return $value;
        }

        $val = strtoupper(trim($value));
        // First pass: replace hyphens and spaces with underscores for consistency
        $val = preg_replace('~[-\s]+~', '_', $val);
        // Second pass: clean up any remaining non-alphanumeric chars except underscores
        $val = preg_replace('~[^A-Z0-9_]~', '', $val) ?: $val;

        // Map common aliases and variations
        $aliases = [
            'POSTNL'           => self::POSTNL,
            'POST_NL'          => self::POSTNL,
            'BPOST'            => self::BPOST,
            'B_POST'           => self::BPOST,
            'DPD'              => self::DPD,
            'GLS'              => self::GLS,
            'DHL'              => self::DHL_FOR_YOU, // Default DHL to DHL_FOR_YOU for backwards compatibility
            'DHLFORYOU'        => self::DHL_FOR_YOU,
            'DHL_FOR_YOU'      => self::DHL_FOR_YOU,
            'DHL_FORYOU'       => self::DHL_FOR_YOU,
            'DHLPARCELCONNECT' => self::DHL_PARCEL_CONNECT,
            'DHL_PARCEL_CONNECT' => self::DHL_PARCEL_CONNECT,
            'DHL_PARCELCONNECT' => self::DHL_PARCEL_CONNECT,
            'DHLEUROPLUS'      => self::DHL_EUROPLUS,
            'DHL_EUROPLUS'     => self::DHL_EUROPLUS,
            'DHL_EURO_PLUS'    => self::DHL_EUROPLUS,
            'UPS'              => self::UPS_STANDARD, // Default UPS to UPS_STANDARD for backwards compatibility
            'UPSSTANDARD'      => self::UPS_STANDARD,
            'UPS_STANDARD'     => self::UPS_STANDARD,
            'UPSEXPRESSSAVER'  => self::UPS_EXPRESS_SAVER,
            'UPS_EXPRESS_SAVER' => self::UPS_EXPRESS_SAVER,
            'UPS_EXPRESSSAVER' => self::UPS_EXPRESS_SAVER,
            'BOL'              => self::BOL,
            'BRT'              => self::BRT,
            'CHEAPCARGO'       => self::CHEAP_CARGO,
            'CHEAP_CARGO'      => self::CHEAP_CARGO,
            'TRUNKRS'          => self::TRUNKRS,
        ];

        if (isset($aliases[$val])) {
            return $aliases[$val];
        }

        // If it's one of the defined constants after cleanup, return it as-is
        if (in_array($val, self::all(), true)) {
            return $val;
        }

        // Fallback: return cleaned/uppercased input so we don't block unknown carriers
        return $val;
    }

    /**
     * Check if a carrier is valid.
     *
     * @param string $carrier
     * @return bool
     */
    public static function isValid(string $carrier): bool
    {
        return in_array($carrier, self::all(), true);
    }
}
