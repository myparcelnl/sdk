<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;

final class HttpCapabilitiesClient implements CapabilitiesClientInterface
{
    private CapabilitiesMapper $mapper;

    private ?ShipmentApi $api;

    /**
     * @param CapabilitiesMapper|null $mapper Maps the request and the response.
     * @param ShipmentApi|null        $api    A client with its own API key and host. Without one, each
     *                                        call creates a client with the API key of the environment.
     */
    public function __construct(?CapabilitiesMapper $mapper = null, ?ShipmentApi $api = null)
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

        $api = $this->api ?? ShipmentApiFactory::make(null, null, $userAgent);
        $coreReq = $this->mapper->mapToCoreApi($request);

        $coreRes = $api->postCapabilities($coreReq, $userAgent);

        return $this->mapper->mapFromCoreApi($coreRes);
    }
}
