<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities\Enum;

final class TransactionType
{
    public const B2C = 'B2C';
    public const B2B = 'B2B';
    public const C2C = 'C2C';

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [self::B2C, self::B2B, self::C2C];
    }

    public static function normalize(?string $value): ?string
    {
        if (null === $value || '' === $value) {
            return $value;
        }
        $upper = strtoupper(trim($value));
        if (in_array($upper, self::all(), true)) {
            return $upper;
        }

        // friendly inputs
        $map = [
            'b2c' => self::B2C,
            'b2b' => self::B2B,
            'c2c' => self::C2C,
        ];

        $k = strtolower(trim($value));
        return $map[$k] ?? $upper;
    }
}
