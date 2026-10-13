<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\Concerns\HasRecordVersion;

/**
 * One shop's lasting relationship with MyParcel.
 *
 * An installation is identified by the thumbprint of its key pair. MyParcel calls its own half of
 * this the AUTH# installation record; this is the shop's half. Everything else in the connect state,
 * the token, the scope and the nonces, is state inside an installation.
 *
 * Store this in durable configuration. It is created at the first start() and only removed when the
 * plugin is uninstalled: disconnecting keeps it, because a reconnect has to reuse the same key pair
 * or MyParcel sees a new shop, and because MyParcel keeps putting the connection id on requests it
 * makes to the shop.
 *
 * It also holds the token challenge, the htm and htu MyParcel sends once on the callback. Those are
 * what a refresh proof is bound to, and nothing hands them out again, so they belong to the
 * installation and not to any one token.
 */
final class ConnectInstallation
{
    use HasRecordVersion;

    /**
     * Bumped only when the fields of this record change.
     */
    private const VERSION = 1;

    /**
     * @var string
     */
    private $encryptedPrivateKey;

    /**
     * @var string|null
     */
    private $connectionId;

    /**
     * @var string|null
     */
    private $challengeHtm;

    /**
     * @var string|null
     */
    private $challengeHtu;

    /**
     * Build an installation record.
     *
     * @param  string      $encryptedPrivateKey An AesGcmCipher envelope, never a readable key.
     * @param  string|null $connectionId        Null until the first successful exchange.
     * @param  string|null $challengeHtm        Null until the first callback.
     * @param  string|null $challengeHtu        Null until the first callback.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function __construct(
        string $encryptedPrivateKey,
        ?string $connectionId = null,
        ?string $challengeHtm = null,
        ?string $challengeHtu = null
    ) {
        if ('' === $encryptedPrivateKey) {
            throw ConnectException::invalidArgument('An installation needs a key');
        }

        $this->encryptedPrivateKey = $encryptedPrivateKey;
        $this->connectionId        = $connectionId;
        $this->challengeHtm        = $challengeHtm;
        $this->challengeHtu        = $challengeHtu;
    }

    /**
     * Get the encrypted key pair. Pass it to AesGcmCipher::decrypt() to use it, and never log it.
     */
    public function getEncryptedPrivateKey(): string
    {
        return $this->encryptedPrivateKey;
    }

    /**
     * Get the id that identifies this connection. MyParcel puts it on requests it makes to the
     * shop, and the shop checks it against this value. It is never sent on calls to MyParcel.
     */
    public function getConnectionId(): ?string
    {
        return $this->connectionId;
    }

    /**
     * Replace the connection id, as a new connect flow issues one.
     */
    public function withConnectionId(?string $connectionId): self
    {
        $clone               = clone $this;
        $clone->connectionId = $connectionId;

        return $clone;
    }

    /**
     * Get the method a refresh proof must claim. Always POST today.
     */
    public function getChallengeHtm(): ?string
    {
        return $this->challengeHtm;
    }

    /**
     * Get the URL a refresh proof must claim: the identity provider token endpoint.
     */
    public function getChallengeHtu(): ?string
    {
        return $this->challengeHtu;
    }

    /**
     * Store the htm and htu every later refresh proof is bound to.
     *
     * MyParcel sends them once, on the callback, and nothing hands them out again, so they live here
     * rather than with the token: an expired token is one refresh away, an expired challenge is a
     * reconnect.
     */
    public function withTokenChallenge(?string $challengeHtm, ?string $challengeHtu): self
    {
        $clone               = clone $this;
        $clone->challengeHtm = $challengeHtm;
        $clone->challengeHtu = $challengeHtu;

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
            'encryptedPrivateKey' => $this->encryptedPrivateKey,
            'connectionId'        => $this->connectionId,
            'challengeHtm'        => $this->challengeHtm,
            'challengeHtu'        => $this->challengeHtu,
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
        $encryptedPrivateKey = self::optionalString($data ?? [], 'encryptedPrivateKey');

        // A stored row can be truncated or half written. Nothing usable reads as absent rather than
        // throwing, because isConnected() asks this question and a settings screen cannot handle an
        // exception. Casting instead would hand back a key of '0' or 'Array' and blame the
        // encryption key later.
        if (!self::hasKnownVersion($data) || null === $encryptedPrivateKey || '' === $encryptedPrivateKey) {
            return null;
        }

        return new self(
            $encryptedPrivateKey,
            self::optionalString($data, 'connectionId'),
            self::optionalString($data, 'challengeHtm'),
            self::optionalString($data, 'challengeHtu')
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function optionalString(array $data, string $field): ?string
    {
        return isset($data[$field]) && is_string($data[$field]) ? $data[$field] : null;
    }
}
