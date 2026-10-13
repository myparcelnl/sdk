<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect\Concerns;

/**
 * The version marker every stored record carries.
 *
 * A consumer writes these arrays into its own storage, so the shape is a contract. The version is
 * there so a record written by a newer SDK reads as absent instead of being misread: the merchant
 * then reconnects, which is recoverable, where a wrong read is not.
 *
 * Each record declares its own VERSION constant, so they can bump independently. The constant lives
 * on the record and not here, because a trait cannot hold one before PHP 8.2 and this SDK runs on
 * 7.4. This is not the version byte inside an AesGcmCipher envelope, which covers the encryption
 * layout rather than the record.
 */
trait HasRecordVersion
{
    /**
     * A record this SDK can read.
     *
     * An older version is accepted: add an upgrade branch to that record's fromArray() when the
     * fields change. A newer one is not, because the fields are unknown. Casting matters because a
     * per-column table or a cache layer can hand back '1' instead of 1, and a strict compare would
     * read every record as absent. For the installation that means start() mints a new key pair and
     * MyParcel sees a new shop, so this must not be strict.
     *
     * @param array<string, mixed>|null $data
     */
    private static function hasKnownVersion(?array $data): bool
    {
        if (null === $data || !isset($data['version']) || !is_numeric($data['version'])) {
            return false;
        }

        return (int) $data['version'] <= self::VERSION;
    }
}
