<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;
use MyParcelNL\Sdk\Concerns\CreatesCoreApiClient;
use UnexpectedValueException;

/**
 * Sends a capabilities request with the Jane Core API client.
 */
final class HttpCapabilitiesClient implements CapabilitiesClientInterface
{
    use CreatesCoreApiClient;

    /**
     * Create the client with a Jane client that keeps its own User-Agent, or with an API key.
     *
     * @param Client|null $api    A client with its own API key, host, headers or User-Agent, such as one
     *                            from ShipmentApiFactory. With a client, `$apiKey` and `$host` are not used.
     * @param string|null $apiKey The API key of the client that the first request creates. Without one,
     *                            ShipmentApiFactory reads `API_KEY`, `API_KEY_NL` or `API_KEY_BE`.
     * @param string|null $host   The host of the client that the first request creates.
     */
    public function __construct(?Client $api = null, ?string $apiKey = null, ?string $host = null)
    {
        $this->api    = $api;
        $this->apiKey = $apiKey;
        $this->host   = $host;
    }

    /**
     * Get the capabilities for a request from the Core API.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\ApiException For an error response.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a value that the spec does not know. No request goes out.
     * @throws UnexpectedValueException For a v1 response, which the request does not ask for.
     */
    public function getCapabilities(CapabilitiesPostCapabilitiesRequestV2 $request): CapabilitiesResponsesCapabilitiesV2
    {
        $coreRes = $this->api()->postCapabilities(requestBody: $request);

        // The generated method declares the v1 and the v2 response. The request asks for v2, so this
        // check only narrows the type.
        if (! $coreRes instanceof CapabilitiesResponsesCapabilitiesV2) {
            throw new UnexpectedValueException('Unexpected response type returned by Client::postCapabilities().');
        }

        return $coreRes;
    }
}
