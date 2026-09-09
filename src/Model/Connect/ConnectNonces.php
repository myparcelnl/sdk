<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Model\Connect\Concerns\HasRecordVersion;

/**
 * Two short lived values, both called a nonce, issued by two different parties.
 *
 * The start nonce is ours: start() makes it, puts it in the connect URL, and matches it against the
 * one the callback sends back. That match is what proves the callback belongs to a flow this shop
 * began. Single use.
 *
 * The DPoP nonce is MyParcel's: the identity provider sends one in a DPoP-Nonce response header and
 * expects it in later proofs. Keeping it means only the first call pays for a retry.
 *
 * They share a record because they need identical treatment: neither is a secret, both are short
 * lived, and both are safe to lose. Only their origin differs, which changes nothing about storing
 * them. ConnectStorageInterface::saveNonces() has the guidance, because that is where a consumer
 * writes the code.
 */
final class ConnectNonces
{
    use HasRecordVersion;

    /**
     * Bumped only when the fields of this record change.
     */
    private const VERSION = 1;

    /**
     * @var string|null
     */
    private $startNonce;

    /**
     * @var int|null
     */
    private $startNonceExpiresAt;

    /**
     * @var string|null
     */
    private $dpopNonce;

    /**
     * Build a nonce record. Both are optional: a record holding neither is a valid empty one.
     */
    public function __construct(?string $startNonce = null, ?int $startNonceExpiresAt = null, ?string $dpopNonce = null)
    {
        $this->startNonce          = $startNonce;
        $this->startNonceExpiresAt = $startNonceExpiresAt;
        $this->dpopNonce           = $dpopNonce;
    }

    /**
     * Get the nonce start() put in the connect URL, for the callback to be matched against.
     */
    public function getStartNonce(): ?string
    {
        return $this->startNonce;
    }

    /**
     * Get when a started flow stops being valid, in epoch seconds.
     */
    public function getStartNonceExpiresAt(): ?int
    {
        return $this->startNonceExpiresAt;
    }

    /**
     * Get the nonce MyParcel last sent, to put in the next proof.
     */
    public function getDpopNonce(): ?string
    {
        return $this->dpopNonce;
    }

    /**
     * Burn the start nonce, so the same callback cannot be used twice.
     */
    public function withoutStartNonce(): self
    {
        $clone                      = clone $this;
        $clone->startNonce          = null;
        $clone->startNonceExpiresAt = null;

        return $clone;
    }

    /**
     * Keep the nonce MyParcel just issued, for the next proof.
     */
    public function withDpopNonce(?string $dpopNonce): self
    {
        $clone            = clone $this;
        $clone->dpopNonce = $dpopNonce;

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
            'version'             => self::VERSION,
            'startNonce'          => $this->startNonce,
            'startNonceExpiresAt' => $this->startNonceExpiresAt,
            'dpopNonce'           => $this->dpopNonce,
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
        if (!self::hasKnownVersion($data)) {
            return null;
        }

        return new self(
            isset($data['startNonce']) ? (string) $data['startNonce'] : null,
            isset($data['startNonceExpiresAt']) ? (int) $data['startNonceExpiresAt'] : null,
            isset($data['dpopNonce']) ? (string) $data['dpopNonce'] : null
        );
    }
}
