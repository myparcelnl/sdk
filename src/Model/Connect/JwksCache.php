<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Model\Connect\Concerns\HasRecordVersion;

/**
 * MyParcel's public keys, as last fetched from /.well-known/jwks.json, with the time of the fetch.
 *
 * The keys verify the signature on every command MyParcel sends. They are public, change rarely,
 * and are fetched again when they are older than an hour or when a command names a key that is not
 * in here. Safe to lose: the next command fetches them again, at one extra round trip.
 *
 * JwksStorageInterface::saveJwks() says where to keep it.
 */
final class JwksCache
{
    use HasRecordVersion;

    /**
     * Bumped only when the fields of this record change.
     */
    private const VERSION = 1;

    /**
     * @var array{keys: array<int, mixed>} The JWKS document as MyParcel served it.
     */
    private $jwks;

    /**
     * @var int Epoch seconds.
     */
    private $fetchedAt;

    /**
     * @param array{keys: array<int, mixed>} $jwks      The decoded JWKS document.
     * @param int                             $fetchedAt When it was fetched, in epoch seconds.
     */
    public function __construct(array $jwks, int $fetchedAt)
    {
        $this->jwks      = $jwks;
        $this->fetchedAt = $fetchedAt;
    }

    /**
     * Get the JWKS document as MyParcel served it.
     *
     * @return array{keys: array<int, mixed>}
     */
    public function getJwks(): array
    {
        return $this->jwks;
    }

    /**
     * Get when the keys were fetched, in epoch seconds.
     */
    public function getFetchedAt(): int
    {
        return $this->fetchedAt;
    }

    /**
     * Flatten the record for a consumer's storage, version included.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'version'   => self::VERSION,
            'jwks'      => $this->jwks,
            'fetchedAt' => $this->fetchedAt,
        ];
    }

    /**
     * Read a stored record back.
     *
     * @param  array<string, mixed>|null $data
     * @return self|null Null when there is nothing usable, so the keys are fetched again.
     */
    public static function fromArray(?array $data): ?self
    {
        if (!self::hasKnownVersion($data)
            || !isset($data['jwks'], $data['fetchedAt'])
            || !is_array($data['jwks'])
            || !is_array($data['jwks']['keys'] ?? null)
            || !is_numeric($data['fetchedAt'])) {
            return null;
        }

        return new self($data['jwks'], (int) $data['fetchedAt']);
    }
}
