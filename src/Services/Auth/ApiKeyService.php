<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Auth;

use MyParcelNL\Sdk\Client\Generated\IamApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\IamApi\ApiException;
use MyParcelNL\Sdk\Client\Generated\IamApi\Model\Principal;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;

/**
 * Service for validating API keys through the generated IAM API client.
 */
final class ApiKeyService
{
    use HasUserAgent;

    private ?DefaultApi $api;

    private string $apiKey;

    private ?string $host;

    public function __construct(
        string $apiKey,
        ?DefaultApi $api = null,
        ?string $host = null
    ) {
        $this->api    = $api;
        $this->apiKey = $apiKey;
        $this->host   = $host;
    }

    /**
     * Check whether the API key is accepted by IAM.
     *
     * Only 401 responses are treated as an invalid key. Other API or transport
     * errors bubble up because they do not prove the key itself is invalid.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\IamApi\ApiException
     */
    public function isValid(): bool
    {
        try {
            $this->getPrincipal();

            return true;
        } catch (ApiException $exception) {
            if (401 === $exception->getCode()) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Fetch the IAM principal for the configured API key.
     *
     * @return \MyParcelNL\Sdk\Client\Generated\IamApi\Model\Principal
     * @throws \MyParcelNL\Sdk\Client\Generated\IamApi\ApiException
     */
    public function getPrincipal(): Principal
    {
        return $this->getApi()->whoamiGet();
    }

    private function getApi(): DefaultApi
    {
        if ($this->api) {
            return $this->api;
        }

        $this->api = IamApiFactory::make($this->apiKey, $this->host, $this->getUserAgentHeader());

        return $this->api;
    }
}
