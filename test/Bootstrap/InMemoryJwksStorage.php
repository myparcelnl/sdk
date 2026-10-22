<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use MyParcelNL\Sdk\Model\Connect\JwksCache;
use MyParcelNL\Sdk\Services\Connect\JwksStorageInterface;

/**
 * A storage that keeps the JWKS record as an array, the way a real one keeps it in a transient.
 */
final class InMemoryJwksStorage implements JwksStorageInterface
{
    /**
     * @var array<string, mixed>|null
     */
    private $row;

    /**
     * @var int
     */
    private $saves = 0;

    public function loadJwks(): ?JwksCache
    {
        return JwksCache::fromArray($this->row);
    }

    public function saveJwks(JwksCache $jwks): void
    {
        $this->saves++;
        $this->row = $jwks->toArray();
    }

    /**
     * The raw array as a consumer would have written it.
     *
     * @return array<string, mixed>|null
     */
    public function row(): ?array
    {
        return $this->row;
    }

    public function saveCount(): int
    {
        return $this->saves;
    }

    /**
     * Put a raw array in place, to test a row from another time or another SDK version.
     *
     * @param array<string, mixed>|null $data
     */
    public function seed(?array $data): void
    {
        $this->row = $data;
    }
}
