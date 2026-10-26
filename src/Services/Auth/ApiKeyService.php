<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Auth;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\WithResponseInterface;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShopType;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUserType;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use UnexpectedValueException;

/**
 * Service for validating API keys through the generated IAM API client.
 */
final class ApiKeyService
{
    use HasUserAgent;

    private ?Client $api;

    private string $apiKey;

    private ?string $host;

    private Serializer $serializer;

    public function __construct(
        string $apiKey,
        ?Client $api = null,
        ?string $host = null
    ) {
        $this->api        = $api;
        $this->apiKey     = $apiKey;
        $this->host       = $host;
        $this->serializer = new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]);
    }

    /**
     * Check whether the API key is accepted by IAM.
     *
     * Only 401 responses are treated as an invalid key. Other API or transport
     * errors bubble up because they do not prove the key itself is invalid.
     *
     * The status comes from the response, not from the exception code: the exceptions that Jane
     * generates for a status in the spec have no code.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\ClientException
     * @throws \UnexpectedValueException When IAM returns a principal type that this SDK does not know.
     */
    public function getPrincipal(): PrincipalShop|PrincipalUser
    {
        // getWhoami() decodes JSON objects to stdClass. The normalizers read arrays.
        $whoami = json_decode(json_encode($this->getApi()->getWhoami(), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        return $this->serializer->denormalize($whoami, $this->principalClass($whoami), 'json', ['skip_validation' => true]);
    }

    /**
     * @param  array<string, mixed> $whoami The decoded whoami response.
     * @return class-string<PrincipalShop|PrincipalUser>
     */
    private function principalClass(array $whoami): string
    {
        $type = $whoami['type'] ?? null;

        return match ($type) {
            PrincipalShopType::Shop->value => PrincipalShop::class,
            PrincipalUserType::User->value => PrincipalUser::class,
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
