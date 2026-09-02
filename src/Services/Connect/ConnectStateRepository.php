<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use MyParcelNL\Sdk\Crypto\AesGcmCipher;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectInstallation;
use MyParcelNL\Sdk\Model\Connect\ConnectNonces;
use MyParcelNL\Sdk\Model\Connect\ConnectState;
use MyParcelNL\Sdk\Model\Connect\ConnectToken;

/**
 * The only class that knows both sides of storage.
 *
 * It turns the three stored records into one readable ConnectState and back, encrypting the private
 * key and the access token on the way out and decrypting them on the way in. So ConnectService never
 * sees an envelope, and a ConnectStorageInterface implementation never sees a readable secret.
 *
 * Each save writes one record. A refresh therefore rewrites the token and leaves the installation and
 * the nonces alone.
 *
 * A read is remembered for the life of this instance, because signing one API call reads the state
 * twice and an order import signs hundreds. Every save forgets it again. Memoised per instance and
 * never statically: one of these belongs to one shop, and a process handling two shops must not
 * hand the second one the first one's key.
 */
final class ConnectStateRepository
{
    /**
     * @var \MyParcelNL\Sdk\Services\Connect\ConnectStorageInterface
     */
    private $storage;

    /**
     * @var \MyParcelNL\Sdk\Crypto\AesGcmCipher
     */
    private $cipher;

    /**
     * @var \MyParcelNL\Sdk\Model\Connect\ConnectState|null
     */
    private $loaded;

    /**
     * @var bool Separate from $loaded, because "nothing stored" is a result worth remembering too.
     */
    private $hasLoaded = false;

    public function __construct(ConnectStorageInterface $storage, AesGcmCipher $cipher)
    {
        $this->storage = $storage;
        $this->cipher  = $cipher;
    }

    /**
     * Read all three records and put them together.
     *
     * @return \MyParcelNL\Sdk\Model\Connect\ConnectState|null Null without an installation: with no
     *                                                        key pair there is nothing to use and
     *                                                        nothing to refresh.
     * @param  bool $fresh Skip the remembered read. Nothing needs this today.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When a secret cannot be decrypted.
     */
    public function load(bool $fresh = false): ?ConnectState
    {
        if ($fresh || !$this->hasLoaded) {
            $this->loaded    = $this->read();
            $this->hasLoaded = true;
        }

        return $this->loaded;
    }

    /**
     * The same, for a caller that cannot work without one.
     *
     * @param  bool $fresh Skip the remembered read.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When this shop has no installation.
     */
    public function loadOrFail(bool $fresh = false): ConnectState
    {
        $state = $this->load($fresh);

        if (null === $state) {
            throw ConnectException::notConnected();
        }

        return $state;
    }

    /**
     * Forget the remembered read. Every save calls this, or the next read would miss its own write.
     */
    private function forget(): void
    {
        $this->loaded    = null;
        $this->hasLoaded = false;
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function read(): ?ConnectState
    {
        $installation = $this->storage->loadInstallation();

        if (null === $installation) {
            return null;
        }

        $state = ConnectState::withKey($this->cipher->decrypt($installation->getEncryptedPrivateKey()))
            ->withConnectionId($installation->getConnectionId());

        $state = $state->withTokenChallenge($installation->getChallengeHtm(), $installation->getChallengeHtu());

        $token = $this->storage->loadToken();

        if (null !== $token) {
            $state = $state->withToken(
                $this->cipher->decrypt($token->getEncryptedAccessToken()),
                $token->getExpiresAt(),
                $token->getScope()
            );
        }

        $nonces = $this->storage->loadNonces();

        if (null !== $nonces) {
            $state = $state
                ->withStartNonce($nonces->getStartNonce(), $nonces->getStartNonceExpiresAt())
                ->withDpopNonce($nonces->getDpopNonce());
        }

        return $state;
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function saveInstallation(ConnectState $state): void
    {
        $this->forget();

        $challenge = $state->getTokenChallenge();

        $this->storage->saveInstallation(new ConnectInstallation(
            $this->cipher->encrypt($state->getPrivateKeyPem()),
            $state->getConnectionId(),
            null === $challenge ? null : $challenge['htm'],
            null === $challenge ? null : $challenge['htu']
        ));
    }

    /**
     * Writes nothing when the state has no token, so a half-built state cannot destroy a good one.
     * Use clearToken() to delete.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function saveToken(ConnectState $state): void
    {
        $this->forget();

        $token = $state->getAccessToken();

        if (null === $token) {
            return;
        }

        $this->storage->saveToken(new ConnectToken(
            $this->cipher->encrypt($token),
            (int) $state->getAccessTokenExpiresAt(),
            (string) $state->getScope()
        ));
    }

    /**
     * Write the start nonce, leaving whatever DPoP nonce is stored in place.
     *
     * Read, change one field, write. Two requests can be in flight at once, so writing the record
     * whole from one state would drop the other's field: a background refresh would wipe the start
     * nonce of a connect flow the merchant is still in, and the callback would be refused.
     */
    public function saveStartNonce(ConnectState $state): void
    {
        $this->forget();

        $nonces = $this->storage->loadNonces() ?? new ConnectNonces();

        $this->storage->saveNonces(
            (new ConnectNonces($state->getStartNonce(), $state->getStartNonceExpiresAt(), $nonces->getDpopNonce()))
        );
    }

    /**
     * Keep the nonce MyParcel just issued, leaving the start nonce in place.
     */
    public function saveDpopNonce(?string $dpopNonce): void
    {
        $this->forget();

        $nonces = $this->storage->loadNonces() ?? new ConnectNonces();

        $this->storage->saveNonces($nonces->withDpopNonce($dpopNonce));
    }

    /**
     * Burn the start nonce once a callback has matched it, so the same callback cannot be used twice.
     *
     * This keeps the DPoP nonce, which the exchange that follows needs. Deleting the whole record
     * here would make every exchange pay a use_dpop_nonce round trip.
     */
    public function burnStartNonce(): void
    {
        $this->forget();

        $nonces = $this->storage->loadNonces();

        if (null === $nonces) {
            return;
        }

        $this->storage->saveNonces($nonces->withoutStartNonce());
    }

    /**
     * Disconnect: the token and everything that only serves it goes, the installation stays.
     */
    public function clearToken(): void
    {
        $this->forget();

        $this->storage->saveToken(null);
    }

    public function clearNonces(): void
    {
        $this->forget();

        $this->storage->saveNonces(null);
    }

    /**
     * Uninstall: everything goes, including the key pair.
     */
    public function clear(): void
    {
        $this->forget();

        $this->storage->clear();
    }
}
