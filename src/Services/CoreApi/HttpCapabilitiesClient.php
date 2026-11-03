<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;

/**
 * Sends a capabilities request with the Jane Core API client.
 *
 * Without a client, the first request creates one with the API key that ShipmentApiFactory finds in
 * the environment: `API_KEY`, `API_KEY_NL` or `API_KEY_BE`, as in v11. That client sends the User-Agent
 * of the SDK. A client that the caller passes keeps its own User-Agent.
 */
final class HttpCapabilitiesClient implements CapabilitiesClientInterface
{
    private CapabilitiesMapper $mapper;

    private ?Client $api;

    public function __construct(?CapabilitiesMapper $mapper = null, ?Client $api = null)
    {
        $this->mapper = $mapper ?? new CapabilitiesMapper();
        $this->api    = $api;
    }

    /**
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\ApiException For an error response.
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException For a value that the spec does not know. No request goes out.
     * @throws InvalidArgumentException For a physical property that is not a whole number, or for a v1 response, which the request does not ask for.
     */
    public function getCapabilities(CapabilitiesRequest $request): CapabilitiesResponse
    {
        $userAgent = sprintf(
            'MyParcelSDK/%s; PHP/%s',
            \defined('\MyParcelNL\Sdk\Sdk::VERSION') ? \MyParcelNL\Sdk\Sdk::VERSION : 'dev',
            PHP_VERSION
        );

        $this->api ??= ShipmentApiFactory::client(null, null, $userAgent);

        $coreRes = $this->api->postCapabilities(requestBody: $this->mapper->mapToCoreApi($request));

        // The generated method declares the v1 and the v2 response. The request asks for v2, so this
        // check only narrows the type.
        if (! $coreRes instanceof CapabilitiesResponsesCapabilitiesV2) {
            throw new InvalidArgumentException('Unexpected response type returned by Client::postCapabilities().');
        }

        return $this->mapper->mapFromCoreApi($coreRes);
    }
}
