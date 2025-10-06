<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities\Enum;

final class DeliveryType
{
    public const STANDARD_DELIVERY = 'STANDARD_DELIVERY';
    public const EVENING_DELIVERY  = 'EVENING_DELIVERY';
    public const MORNING_DELIVERY  = 'MORNING_DELIVERY';
    public const SAME_DAY_DELIVERY = 'SAME_DAY_DELIVERY';
    public const PICKUP_DELIVERY   = 'PICKUP_DELIVERY';
    public const EXPRESS_DELIVERY  = 'EXPRESS_DELIVERY';

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::STANDARD_DELIVERY,
            self::EVENING_DELIVERY,
            self::MORNING_DELIVERY,
            self::SAME_DAY_DELIVERY,
            self::PICKUP_DELIVERY,
            self::EXPRESS_DELIVERY,
        ];
    }

    /**
     * Accept friendly values like "standard", "evening", etc. and normalize to enum.
     */
    public static function normalize(?string $value): ?string
    {
        if (null === $value || '' === $value) {
            return $value;
        }

        // passthrough if already enum
        if (in_array($value, self::all(), true)) {
            return $value;
        }

        $map = [
            'standard' => self::STANDARD_DELIVERY,
            'evening'  => self::EVENING_DELIVERY,
            'morning'  => self::MORNING_DELIVERY,
            'same_day' => self::SAME_DAY_DELIVERY,
            'sameday'  => self::SAME_DAY_DELIVERY,
            'pickup'   => self::PICKUP_DELIVERY,
            'express'  => self::EXPRESS_DELIVERY,
        ];

        $k = strtolower(trim($value));
        return $map[$k] ?? $value; // fallback to original string so we don't hard fail
    }
}
