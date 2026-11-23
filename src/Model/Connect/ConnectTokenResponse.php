<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use DateTimeImmutable;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse200;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse200;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Exception\ConnectException;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use Throwable;

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
     * @var \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse200|\MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse200
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
     * @var int Epoch seconds when the answer was read, which the expiries count from.
     */
    private $receivedAt;

    /**
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse200|\MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse200 $model
     */
    private function __construct($model, ?string $connectionId, ?string $dpopNonce)
    {
        $this->model        = $model;
        $this->connectionId = $connectionId;
        $this->dpopNonce    = $dpopNonce;
        // MyParcel counts from when it built the answer, so the expiries come out late by the network
        // delay. Accepted: the refresh leeway and the month of reconnect notice cover a few seconds.
        $this->receivedAt   = time();
    }

    /**
     * Wrap what /connect/token answered.
     *
     * @param  array<string, mixed> $body      The decoded body of the answer.
     * @param  string|null          $dpopNonce The DPoP-Nonce response header, when one was sent.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the body is not a token of the spec.
     */
    public static function fromExchange(array $body, ?string $dpopNonce = null): self
    {
        /** @var ConnectTokenPostResponse200 $model */
        $model = self::read($body, ConnectTokenPostResponse200::class);

        return new self($model, $model->connectionId, $dpopNonce);
    }

    /**
     * Wrap what /connect/refresh answered.
     *
     * @param  array<string, mixed> $body      The decoded body of the answer.
     * @param  string|null          $dpopNonce The DPoP-Nonce response header, when one was sent.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the body is not a token of the spec.
     */
    public static function fromRefresh(array $body, ?string $dpopNonce = null): self
    {
        return new self(self::read($body, ConnectRefreshPostResponse200::class), null, $dpopNonce);
    }

    public function getAccessToken(): string
    {
        return $this->model->accessToken;
    }

    /**
     * Null on a refresh: the connectionId is issued once, at the exchange.
     */
    public function getConnectionId(): ?string
    {
        return $this->connectionId;
    }

    /**
     * Seconds until the access token expires, counted from when MyParcel issued it.
     */
    public function getExpiresIn(): int
    {
        return $this->model->expiresIn;
    }

    /**
     * When the access token expires, in UTC.
     */
    public function getExpiresAt(): DateTimeImmutable
    {
        return new DateTimeImmutable('@' . ($this->receivedAt + $this->getExpiresIn()));
    }

    /**
     * Seconds until the refresh token expires, counted from when MyParcel issued it.
     */
    public function getRefreshExpiresIn(): int
    {
        return $this->model->refreshExpiresIn;
    }

    /**
     * When the refresh token expires, in UTC. After this moment a refresh fails and the shop has
     * to connect again.
     */
    public function getRefreshExpiresAt(): DateTimeImmutable
    {
        return new DateTimeImmutable('@' . ($this->receivedAt + $this->getRefreshExpiresIn()));
    }

    public function getScope(): string
    {
        return $this->model->scope;
    }

    public function getDpopNonce(): ?string
    {
        return $this->dpopNonce;
    }

    /**
     * Read a body into its generated model and check it against the spec.
     *
     * The generated models check a value when it goes out, not when it comes in. So this normalizes
     * the model that it read: that runs the check of the spec on what MyParcel sent.
     *
     * This is stricter than the other reads of the SDK, which keep a value that the spec does not
     * have. The SDK sends the token as a DPoP token, so it refuses an answer with another
     * `tokenType`.
     *
     * @param  array<string, mixed> $body
     * @param  class-string         $class
     * @return object
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private static function read(array $body, string $class)
    {
        $serializer = new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]);

        try {
            $model = $serializer->denormalize($body, $class);

            $serializer->normalize($model, 'json');
        } catch (ValidationException $exception) {
            $problems = [];

            foreach ($exception->getViolationList() as $violation) {
                $problems[] = trim($violation->getPropertyPath() . ' ' . $violation->getMessage());
            }

            throw ConnectException::invalidArgument(
                sprintf('MyParcel returned an unusable token: %s', implode('; ', $problems))
            );
        } catch (Throwable $exception) {
            // A field with the wrong type, such as text where the spec has a number.
            throw ConnectException::invalidArgument(
                sprintf('MyParcel returned an unusable token: %s', $exception->getMessage())
            );
        }

        return $model;
    }
}
