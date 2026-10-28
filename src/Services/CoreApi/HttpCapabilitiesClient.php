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
 * Without a client, the first request creates one with the API key from the `API_KEY` environment variable, as in v11.
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

    public function getCapabilities(CapabilitiesRequest $request): CapabilitiesResponse
    {
        $userAgent = sprintf(
            'MyParcelSDK/%s; PHP/%s',
            \defined('\MyParcelNL\Sdk\Sdk::VERSION') ? \MyParcelNL\Sdk\Sdk::VERSION : 'dev',
            PHP_VERSION
        );

        $this->api ??= ShipmentApiFactory::client(null, null, $userAgent);

        $coreRes = $this->api->postCapabilities(
            $this->mapper->mapToCoreApi($request),
            ['User-Agent' => $userAgent]
        );

        if (! $coreRes instanceof CapabilitiesResponsesCapabilitiesV2) {
            throw new InvalidArgumentException('Unexpected response type returned by Client::postCapabilities().');
        }

        return $this->mapper->mapFromCoreApi($coreRes);
    }
}
