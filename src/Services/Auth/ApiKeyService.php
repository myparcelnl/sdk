<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Auth;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Client\Jane\SkipValidationOnReadSerializer;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;
use UnexpectedValueException;

/**
 * Checks an API key with IAM and reads its principal.
 *
 * An API key belongs to a shop, so IAM answers with a `PrincipalShop`. The spec also describes a
 * `PrincipalUser`, the answer for a user token, and this service reads that one too.
 */
final class ApiKeyService
{
    use HasUserAgent;

    private ?Client $api;

    private string $apiKey;

    private ?string $host;

    private SkipValidationOnReadSerializer $serializer;

    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $host = null
    ) {
        $this->api        = $api;
        $this->apiKey     = $apiKey;
        $this->host       = $host;
        $this->serializer = JaneClientFactory::iamSerializer();
    }

    /**
     * Check whether IAM accepts the API key.
     *
     * Only a 401 means that the key is invalid. Another error says nothing about the key, so this
     * method throws it. A 200 with a JSON content type proves the key, also for a principal type that
     * the SDK does not know.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ServerException For a 500 with an `application/problem+json` body.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException For an error status other than 401 that the spec does not declare, or for a 200 without a JSON content type.
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface For a network error or a timeout.
     * @throws \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException For a 200 with a JSON content type and a body that is not valid JSON, such as an empty body.
     */
    public function isValid(): bool
    {
        try {
            $this->getApi()->getWhoami();

            return true;
        } catch (ClientException $exception) {
            // Every `ClientException` of the IAM client carries the HTTP status as its code.
            if (401 === $exception->getCode()) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Fetch the IAM principal for the configured API key.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ServerException For a 500 with an `application/problem+json` body.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException For a 401, for an error status that the spec does not declare, or for a 200 without a JSON content type.
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface For a network error or a timeout.
     * @throws \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException For a 200 with a JSON content type and a body that is not valid JSON, such as an empty body.
     * @throws \UnexpectedValueException When IAM returns a principal type that this SDK does not know.
     */
    public function getPrincipal(): PrincipalShop|PrincipalUser
    {
        // The spec describes the response as a oneOf of PrincipalShop and PrincipalUser with a
        // discriminator on `type`. Jane generates no model for it (janephp/janephp#1156), so
        // getWhoami() returns the decoded JSON. This method picks the model and reads the JSON with
        // the serializer of the client, which skips the validation on read, as it does for every response.
        $whoami = $this->getApi()->getWhoami();

        return $this->serializer->deserialize(json_encode($whoami, JSON_THROW_ON_ERROR), $this->principalClass($whoami->type ?? null), 'json');
    }

    /**
     * Pick the model that the discriminator on `type` in the spec describes.
     *
     * @todo janephp/janephp#1156: remove this method when Jane generates a model for a oneOf response
     *       with a discriminator, such as the whoami response.
     *
     * @return class-string<PrincipalShop|PrincipalUser>
     */
    private function principalClass(mixed $type): string
    {
        // Jane generates `type` as a plain string, not as an enum, so the values are literal here.
        return match ($type) {
            'SHOP' => PrincipalShop::class,
            'USER' => PrincipalUser::class,
            default => throw new UnexpectedValueException('IAM returned an unknown principal type: ' . json_encode($type)),
        };
    }

    private function getApi(): Client
    {
        if ($this->api) {
            return $this->api;
        }

        $this->api = IamApiFactory::make($this->apiKey, $this->host, $this->getUserAgentHeader());

        return $this->api;
    }
}
