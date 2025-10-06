<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities\Enum;

final class Direction
{
    public const OUTBOUND = 'OUTBOUND';
    public const INBOUND  = 'INBOUND';

    // Legacy aliases for backward compatibility
    public const OUTWARD = self::OUTBOUND;
    public const RETURN  = self::INBOUND;

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [self::OUTBOUND, self::INBOUND];
    }

    public static function normalize(?string $value): ?string
    {
        if (null === $value || '' === $value) {
            return $value;
        }

        if (in_array($value, self::all(), true)) {
            return $value;
        }

        $map = [
            'outward'  => self::OUTBOUND,
            'outbound' => self::OUTBOUND,
            'inbound'  => self::INBOUND,
            'return'   => self::INBOUND,
            'retour'   => self::INBOUND,
        ];

        $k = strtolower(trim($value));
        return $map[$k] ?? $value;
    }
}
