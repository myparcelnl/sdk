<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use MyParcelNL\Sdk\Model\Connect\JwksCache;

/**
 * Where MyParcel's public keys are kept between commands. You write this.
 *
 * One record, with toArray() and a static fromArray() like the connect records, so an
 * implementation is one line each way:
 *
 *     public function saveJwks(JwksCache $jwks): void
 *     {
 *         set_transient('myparcel_jwks', json_encode($jwks->toArray()), HOUR_IN_SECONDS);
 *     }
 *
 *     public function loadJwks(): ?JwksCache
 *     {
 *         $raw = get_transient('myparcel_jwks');
 *
 *         return $raw ? JwksCache::fromArray(json_decode($raw, true)) : null;
 *     }
 *
 * Nothing in it is a secret. The same place as the connect nonces is fine.
 */
interface JwksStorageInterface
{
    /**
     * @return \MyParcelNL\Sdk\Model\Connect\JwksCache|null Null when nothing is stored.
     */
    public function loadJwks(): ?JwksCache;

    /**
     * Keep this for about an hour. A cache entry or a transient works, because a command arrives in
     * a background request where there is no session.
     *
     * Safe to lose: the next command fetches the keys again. Keeping a stale one costs the same,
     * because the SDK checks the age itself.
     */
    public function saveJwks(JwksCache $jwks): void;
}
