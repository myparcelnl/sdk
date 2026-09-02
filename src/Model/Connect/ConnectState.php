<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Exception\ConnectException;

/**
 * Everything the SDK knows about one shop's connection, with the secrets readable.
 *
 * This is the working view: ConnectService reads and returns it, and you read it to see the scope,
 * the connection id or whether there is a token. It never reaches storage. The three records do
 * that, and ConnectStateRepository maps between them, encrypting on the way out.
 *
 * Immutable: every with* method returns a copy.
 */
final class ConnectState
{
    /**
     * @var string
     */
    private $privateKeyPem;

    /**
     * @var string|null
     */
    private $accessToken;

    /**
     * @var int|null
     */
    private $accessTokenExpiresAt;

    /**
     * @var string|null
     */
    private $connectionId;

    /**
     * @var string|null
     */
    private $scope;

    /**
     * @var string|null
     */
    private $challengeHtm;

    /**
     * @var string|null
     */
    private $challengeHtu;

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

    private function __construct(string $privateKeyPem)
    {
        $this->privateKeyPem = $privateKeyPem;
    }

    /**
     * A shop that has a key pair and nothing else. This is what start() writes first.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public static function withKey(string $privateKeyPem): self
    {
        if ('' === $privateKeyPem) {
            throw ConnectException::invalidArgument('A connect state needs a key');
        }

        return new self($privateKeyPem);
    }

    /**
     * The key pair, readable. Signs every proof.
     */
    public function getPrivateKeyPem(): string
    {
        return $this->privateKeyPem;
    }

    /**
     * The access token, readable. Null when the shop has not finished connecting, or disconnected.
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * When the token stops working, in epoch seconds.
     */
    public function getAccessTokenExpiresAt(): ?int
    {
        return $this->accessTokenExpiresAt;
    }

    /**
     * MyParcel's id for this connection. Keep it: MyParcel puts it on requests it makes to the shop.
     */
    public function getConnectionId(): ?string
    {
        return $this->connectionId;
    }

    /**
     * The scopes MyParcel granted, space delimited.
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * The htm and htu every token and refresh proof is bound to, as MyParcel sent them on the
     * callback. Null until the shop has connected.
     *
     * @return array<string, string>|null ['htm' => ..., 'htu' => ...]
     */
    public function getTokenChallenge(): ?array
    {
        if (null === $this->challengeHtm || null === $this->challengeHtu) {
            return null;
        }

        return ['htm' => $this->challengeHtm, 'htu' => $this->challengeHtu];
    }

    /**
     * The single use value start() put in the connect URL, to match against the callback.
     */
    public function getStartNonce(): ?string
    {
        return $this->startNonce;
    }

    public function getStartNonceExpiresAt(): ?int
    {
        return $this->startNonceExpiresAt;
    }

    /**
     * The last DPoP-Nonce MyParcel issued. Goes in the next token or refresh proof.
     */
    public function getDpopNonce(): ?string
    {
        return $this->dpopNonce;
    }

    public function withConnectionId(?string $connectionId): self
    {
        $clone               = clone $this;
        $clone->connectionId = $connectionId;

        return $clone;
    }

    /**
     * After an exchange or a refresh.
     */
    public function withToken(string $accessToken, int $expiresAt, string $scope): self
    {
        $clone                       = clone $this;
        $clone->accessToken          = $accessToken;
        $clone->accessTokenExpiresAt = $expiresAt;
        $clone->scope                = $scope;

        return $clone;
    }

    /**
     * The htm and htu from the callback, which every later refresh proof is bound to.
     */
    public function withTokenChallenge(?string $challengeHtm, ?string $challengeHtu): self
    {
        $clone               = clone $this;
        $clone->challengeHtm = $challengeHtm;
        $clone->challengeHtu = $challengeHtu;

        return $clone;
    }

    /**
     * After a disconnect or a revocation: no token.
     *
     * The challenge stays. It belongs to the installation, and a reconnect overwrites it with a fresh
     * pair anyway, so there is nothing to gain by dropping it here.
     */
    public function withoutToken(): self
    {
        $clone                       = clone $this;
        $clone->accessToken          = null;
        $clone->accessTokenExpiresAt = null;
        $clone->scope                = null;

        return $clone;
    }

    public function withStartNonce(?string $startNonce, ?int $expiresAt): self
    {
        $clone                      = clone $this;
        $clone->startNonce          = $startNonce;
        $clone->startNonceExpiresAt = $expiresAt;

        return $clone;
    }

    public function withDpopNonce(?string $dpopNonce): self
    {
        $clone            = clone $this;
        $clone->dpopNonce = $dpopNonce;

        return $clone;
    }
}
