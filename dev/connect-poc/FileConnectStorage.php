<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Dev\ConnectPoc;

use MyParcelNL\Sdk\Model\Connect\ConnectInstallation;
use MyParcelNL\Sdk\Model\Connect\ConnectNonces;
use MyParcelNL\Sdk\Model\Connect\ConnectToken;
use MyParcelNL\Sdk\Services\Connect\ConnectStorageInterface;
use RuntimeException;

/**
 * An example implementation of ConnectStorageInterface, backed by one JSON file.
 *
 * Read this to see the shape your own storage needs: seven methods, three records, and one array
 * each way. Every record has toArray() and a static fromArray(), so each method is close to one
 * line. That part is worth copying.
 *
 * A JSON file is not. Use your platform's own settings store or a database table instead:
 *
 * - Writes here are not atomic and take no lock. Each save reads the whole file, changes one
 *   record, and writes it back, so two requests at the same time lose one of the two writes. A
 *   shop runs cron jobs next to web requests, so that happens in practice.
 * - Anything that can read the directory can read the file. Filesystem permissions are the only
 *   thing protecting it, and a backup or a copied container image takes it along.
 * - There is nothing here to separate one shop from another, and no way to query.
 *
 * What the file holds is less alarming than it looks: the SDK encrypts the private key and the
 * access token before they arrive, so those two are ciphertext. The connection id and the nonces
 * are stored as they are, and neither is a secret on its own.
 *
 * The three records have different lifetimes, which is why the interface splits them. The
 * interface documents each one. In short: the installation must last as long as the shop, the
 * token may be thrown away at any time, and the nonces are safe to lose.
 */
final class FileConnectStorage implements ConnectStorageInterface
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Get the shop's key pair, its connection id and its token challenge.
     *
     * Return null when nothing is stored: that is how the SDK knows this shop has never connected.
     * fromArray() takes null and gives back null, so a missing record needs no branch of its own.
     */
    public function loadInstallation(): ?ConnectInstallation
    {
        return ConnectInstallation::fromArray($this->read('installation'));
    }

    /**
     * Store the record that lives as long as the shop does.
     */
    public function saveInstallation(ConnectInstallation $installation): void
    {
        $this->write('installation', $installation->toArray());
    }

    /**
     * Get the access token and its expiry.
     */
    public function loadToken(): ?ConnectToken
    {
        return ConnectToken::fromArray($this->read('token'));
    }

    /**
     * Store the token. Null clears it, which is what disconnect() does.
     */
    public function saveToken(?ConnectToken $token): void
    {
        $this->write('token', null === $token ? null : $token->toArray());
    }

    /**
     * Get the two short lived nonces.
     */
    public function loadNonces(): ?ConnectNonces
    {
        return ConnectNonces::fromArray($this->read('nonces'));
    }

    /**
     * Store the nonces. Losing these costs one round trip, nothing more.
     */
    public function saveNonces(?ConnectNonces $nonces): void
    {
        $this->write('nonces', null === $nonces ? null : $nonces->toArray());
    }

    /**
     * Throw everything away, as uninstall() does.
     */
    public function clear(): void
    {
        $this->put([]);
    }

    /**
     * Show everything stored, so the page can display it. Not part of the interface.
     *
     * @return array<string, mixed>
     */
    public function everything(): array
    {
        return $this->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    private function read(string $record): ?array
    {
        $all = $this->all();

        return isset($all[$record]) && is_array($all[$record]) ? $all[$record] : null;
    }

    /**
     * @param array<string, mixed>|null $data
     */
    private function write(string $record, ?array $data): void
    {
        $all = $this->all();

        if (null === $data) {
            unset($all[$record]);
        } else {
            $all[$record] = $data;
        }

        $this->put($all);
    }

    /**
     * @return array<string, mixed>
     */
    private function all(): array
    {
        if (!is_file($this->path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($this->path), true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param array<string, mixed> $all
     */
    private function put(array $all): void
    {
        $written = file_put_contents($this->path, (string) json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        if (false === $written) {
            throw new RuntimeException(sprintf('Could not write %s', $this->path));
        }
    }
}
