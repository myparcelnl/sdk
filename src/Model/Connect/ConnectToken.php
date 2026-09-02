<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\Concerns\HasRecordVersion;

/**
 * The access token a shop is currently using, and what a refresh needs.
 *
 * Lives from the callback until the shop disconnects or MyParcel revokes the connection. Replaced on
 * every refresh.
 *
 * Nothing here outlives the token itself: the token challenge a refresh needs is on the
 * ConnectInstallation. So losing this record costs one refresh and never a reconnect, which is what
 * makes it safe in a cache with a lifetime.
 */
final class ConnectToken
{
    use HasRecordVersion;

    /**
     * Bumped only when the fields of this record change.
     */
    private const VERSION = 1;

    /**
     * @var string
     */
    private $encryptedAccessToken;

    /**
     * @var int
     */
    private $expiresAt;

    /**
     * @var string
     */
    private $scope;

    /**
     * Build a token record.
     *
     * @param  string $encryptedAccessToken An AesGcmCipher envelope, never a readable token.
     * @param  int    $expiresAt            Epoch seconds, worked out from expiresIn at the exchange.
     * @param  string $scope                The scopes MyParcel granted.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function __construct(string $encryptedAccessToken, int $expiresAt, string $scope)
    {
        if ('' === $encryptedAccessToken) {
            throw ConnectException::invalidArgument('A token needs an access token');
        }

        $this->encryptedAccessToken = $encryptedAccessToken;
        $this->expiresAt            = $expiresAt;
        $this->scope                = $scope;
    }

    /**
     * Get the encrypted access token. Pass it to AesGcmCipher::decrypt() to use it, and never log it.
     */
    public function getEncryptedAccessToken(): string
    {
        return $this->encryptedAccessToken;
    }

    /**
     * Get when the token stops working, in epoch seconds.
     */
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    /**
     * Get the scopes MyParcel granted, space delimited as it sent them.
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * Replace the token, its expiry and its scope, as a refresh answer does.
     */
    public function withAccessToken(string $encryptedAccessToken, int $expiresAt, string $scope): self
    {
        $clone                       = clone $this;
        $clone->encryptedAccessToken = $encryptedAccessToken;
        $clone->expiresAt            = $expiresAt;
        $clone->scope                = $scope;

        return $clone;
    }

    /**
     * Flatten the record for a consumer's storage, version included.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'version'              => self::VERSION,
            'encryptedAccessToken' => $this->encryptedAccessToken,
            'expiresAt'            => $this->expiresAt,
            'scope'                => $this->scope,
        ];
    }

    /**
     * Read a stored record back.
     *
     * @param  array<string, mixed>|null $data
     * @return self|null Null when there is nothing usable, so the caller treats it as absent.
     */
    public static function fromArray(?array $data): ?self
    {
        $encryptedAccessToken = self::optionalString($data ?? [], 'encryptedAccessToken');
        $scope                = self::optionalString($data ?? [], 'scope');
        $expiresAt            = $data['expiresAt'] ?? null;

        // Nothing usable reads as absent rather than throwing or carrying a cast value: an array
        // becomes 'Array', and a non-numeric expiry becomes 0, which reads as expired at the epoch.
        // A numeric string is accepted for the same reason hasKnownVersion() accepts one.
        if (!self::hasKnownVersion($data)
            || null === $encryptedAccessToken
            || '' === $encryptedAccessToken
            || null === $scope
            || !is_numeric($expiresAt)
        ) {
            return null;
        }

        return new self($encryptedAccessToken, (int) $expiresAt, $scope);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function optionalString(array $data, string $field): ?string
    {
        return isset($data[$field]) && is_string($data[$field]) ? $data[$field] : null;
    }
}
