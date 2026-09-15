<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use MyParcelNL\Sdk\Model\Connect\ConnectInstallation;
use MyParcelNL\Sdk\Model\Connect\ConnectNonces;
use MyParcelNL\Sdk\Model\Connect\ConnectToken;
use MyParcelNL\Sdk\Services\Connect\ConnectStorageInterface;

/**
 * A storage that keeps the three records in arrays, the way a real one keeps them in wp_options.
 *
 * It stores the arrays rather than the objects, so a test sees exactly what a consumer would write,
 * and counts the calls, so a test can prove that saving one record leaves the others alone.
 */
final class InMemoryConnectStorage implements ConnectStorageInterface
{
    /**
     * @var array<string, array|null>
     */
    private $rows = ['installation' => null, 'token' => null, 'nonces' => null];

    /**
     * @var array<string, int>
     */
    private $calls = [];

    public function loadInstallation(): ?ConnectInstallation
    {
        $this->count(__FUNCTION__);

        return ConnectInstallation::fromArray($this->rows['installation']);
    }

    public function saveInstallation(ConnectInstallation $installation): void
    {
        $this->count(__FUNCTION__);

        $this->rows['installation'] = $installation->toArray();
    }

    public function loadToken(): ?ConnectToken
    {
        $this->count(__FUNCTION__);

        return ConnectToken::fromArray($this->rows['token']);
    }

    public function saveToken(?ConnectToken $token): void
    {
        $this->count(__FUNCTION__);

        $this->rows['token'] = null === $token ? null : $token->toArray();
    }

    public function loadNonces(): ?ConnectNonces
    {
        $this->count(__FUNCTION__);

        return ConnectNonces::fromArray($this->rows['nonces']);
    }

    public function saveNonces(?ConnectNonces $nonces): void
    {
        $this->count(__FUNCTION__);

        $this->rows['nonces'] = null === $nonces ? null : $nonces->toArray();
    }

    public function clear(): void
    {
        $this->count(__FUNCTION__);

        $this->rows = ['installation' => null, 'token' => null, 'nonces' => null];
    }

    /**
     * The raw array as a consumer would have written it.
     *
     * @return array<string, mixed>|null
     */
    public function row(string $record): ?array
    {
        return $this->rows[$record];
    }

    /**
     * Everything stored, as one JSON string, for asserting that no secret is readable.
     */
    public function everythingAsJson(): string
    {
        // Unescaped, so an assertion can name a URL the way it is written in the code.
        return (string) json_encode($this->rows, JSON_UNESCAPED_SLASHES);
    }

    public function callCount(string $method): int
    {
        return $this->calls[$method] ?? 0;
    }

    /**
     * Put a raw array in place, to test what happens with a row an older or newer SDK wrote.
     *
     * @param array<string, mixed>|null $data
     */
    public function seed(string $record, ?array $data): void
    {
        $this->rows[$record] = $data;
    }

    private function count(string $method): void
    {
        $this->calls[$method] = ($this->calls[$method] ?? 0) + 1;
    }
}
