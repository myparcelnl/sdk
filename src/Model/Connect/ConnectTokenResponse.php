<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectRefreshPost200Response;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost200Response;
use MyParcelNL\Sdk\Exception\ConnectException;

/**
 * What /connect/token and /connect/refresh answer with.
 *
 * The two answers are generated as separate models, because a refresh does not repeat the
 * connectionId. This holds either one, so the rest of the SDK has one type to read a token off.
 *
 * It adds the two things the generated models cannot carry: the DPoP-Nonce, which is a response
 * header rather than a body field, and the connectionId being absent on a refresh.
 */
final class ConnectTokenResponse
{
    /**
     * @var \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectRefreshPost200Response|\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost200Response
     */
    private $model;

    /**
     * @var string|null
     */
    private $connectionId;

    /**
     * @var string|null
     */
    private $dpopNonce;

    /**
     * @param  \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectRefreshPost200Response|\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost200Response $model
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function __construct($model, ?string $connectionId, ?string $dpopNonce)
    {
        $invalid = $model->listInvalidProperties();

        if ([] !== $invalid) {
            throw ConnectException::invalidArgument(
                sprintf('MyParcel returned an unusable token: %s', implode('; ', $invalid))
            );
        }

        // The spec puts exclusiveMinimum 0 on expiresIn, but the generator emits only the null check.
        if ((int) $model->getExpiresIn() < 1) {
            throw ConnectException::invalidArgument('MyParcel returned no usable expiresIn');
        }

        $this->model        = $model;
        $this->connectionId = $connectionId;
        $this->dpopNonce    = $dpopNonce;
    }

    /**
     * @param  string|null $dpopNonce The DPoP-Nonce response header, when there was one.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public static function fromExchange(ConnectTokenPost200Response $model, ?string $dpopNonce = null): self
    {
        return new self($model, $model->getConnectionId(), $dpopNonce);
    }

    /**
     * @param  string|null $dpopNonce The DPoP-Nonce response header, when there was one.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public static function fromRefresh(ConnectRefreshPost200Response $model, ?string $dpopNonce = null): self
    {
        return new self($model, null, $dpopNonce);
    }

    public function getAccessToken(): string
    {
        return (string) $this->model->getAccessToken();
    }

    /**
     * Null on a refresh answer. The value from the exchange stays valid, so keep the stored one.
     */
    public function getConnectionId(): ?string
    {
        return $this->connectionId;
    }

    /**
     * How long the access token lasts, in seconds from now.
     */
    public function getExpiresIn(): int
    {
        return (int) $this->model->getExpiresIn();
    }

    public function getScope(): string
    {
        return (string) $this->model->getScope();
    }

    /**
     * The DPoP-Nonce header, when MyParcel sent one. Store it and put it in the next proof.
     */
    public function getDpopNonce(): ?string
    {
        return $this->dpopNonce;
    }
}
