<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities\Enum;

final class PackageType
{
    public const PACKAGE = 'PACKAGE';
    public const MAILBOX = 'MAILBOX';
    public const LETTER  = 'LETTER';
    public const PALLET  = 'PALLET';

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::PACKAGE,
            self::MAILBOX,
            self::LETTER,
            self::PALLET,
        ];
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
            'package' => self::PACKAGE,
            'mailbox' => self::MAILBOX,
            'letter'  => self::LETTER,
            'pallet'  => self::PALLET,
        ];

        $k = strtolower(trim($value));
        return $map[$k] ?? $value;
    }
}
