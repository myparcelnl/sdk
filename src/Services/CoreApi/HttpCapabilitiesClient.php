<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use UnexpectedValueException;

/**
 * Sends a capabilities request with the Jane Core API client.
 */
final class HttpCapabilitiesClient implements CapabilitiesClientInterface
{
    use HasUserAgent;

    private ?Client $api;

    /**
     * Create the client with a Jane client that has its own API key, host, headers
     * or User-Agent. Without a Jane client, the first request creates one with the API key that
     * ShipmentApiFactory reads from `API_KEY`, `API_KEY_NL` or `API_KEY_BE`, and with the User-Agent
     * of this client, see HasUserAgent.
     */
    public function __construct(?Client $api = null)
    {
        $this->api = $api;
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

    /**
     * The first request creates the client with the User-Agent of this client, so a proposition that
     * is set after the constructor reaches the API. The environment is read then, once.
     */
    private function api(): Client
    {
        return $this->api ??= ShipmentApiFactory::make(null, null, $this->getUserAgentHeader());
    }
}
