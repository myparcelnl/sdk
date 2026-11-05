<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Auth;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\WithResponseInterface;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Client\Jane\SkipValidationOnReadSerializer;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;
use UnexpectedValueException;

/**
 * Service for validating API keys through the generated IAM API client.
 *
 * getPrincipal() returns the Jane models PrincipalShop and PrincipalUser, without v11 getters: the
 * PDK and the Magento module do not call it, and the v11 docs read no field of the principal. The
 * class tells a shop key from a user key.
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
     * method throws it. The status comes from the response: a 401 with a body that is not JSON throws
     * a `BadResponseException` instead of a `GetWhoamiUnauthorizedException`.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ServerException For a 500 with an `application/problem+json` body.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException For an error status other than 401 that the spec does not declare.
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface For a network error or a timeout.
     * @throws \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException For a 200 with a body that is not valid JSON.
     * @throws \UnexpectedValueException When IAM returns a principal type that this SDK does not know.
     */
    public function isValid(): bool
    {
        try {
            $this->getPrincipal();

            return true;
        } catch (ClientException $exception) {
            if ($exception instanceof WithResponseInterface && 401 === $exception->getResponse()?->getStatusCode()) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Fetch the IAM principal for the configured API key.
     *
     * The spec describes the response as PrincipalShop or PrincipalUser, chosen by `type`. The
     * generated client cannot choose a model for it, so getWhoami() returns the decoded JSON and
     * this method picks the model.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ServerException For a 500 with an `application/problem+json` body.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException For a 401, or for an error status that the spec does not declare.
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface For a network error or a timeout.
     * @throws \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException For a 200 with a body that is not valid JSON.
     * @throws \UnexpectedValueException When IAM returns a principal type that this SDK does not know.
     */
    public function getPrincipal(): PrincipalShop|PrincipalUser
    {
        // getWhoami() returns the decoded JSON. Encode it again, so the client serializer reads it
        // like any other response, without validation.
        $json   = json_encode($this->getApi()->getWhoami(), JSON_THROW_ON_ERROR);
        $whoami = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $this->serializer->deserialize($json, $this->principalClass($whoami), 'json');
    }

    /**
     * Pick the model that the discriminator on `type` in the spec describes.
     *
     * @todo Remove this method when Jane generates a model for the whoami response: the spec describes
     *       it as a oneOf with a discriminator, and the generated GetWhoami endpoint has no target class.
     *
     * @param  array<string, mixed> $whoami The decoded whoami response.
     * @return class-string<PrincipalShop|PrincipalUser>
     */
    private function principalClass(array $whoami): string
    {
        $type = $whoami['type'] ?? null;

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
