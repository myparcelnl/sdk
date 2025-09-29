<?php

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Services\CoreApi\CapabilitiesClientInterface;

final class HttpCapabilitiesClient implements CapabilitiesClientInterface
{
    /** @var CapabilitiesMapper */
    private $mapper;

    public function __construct(CapabilitiesMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getCapabilities(CapabilitiesRequest $request): CapabilitiesResponse
    {
        // 1) Haal de gegenereerde DefaultApi via onze factory
        $api = CapabilitiesClientFactory::make(); // evt. make($sandboxUrl)

        // 2) Map jouw SDK-request naar het generated request-model
        $coreReq = $this->mapper->mapToCoreApi($request);

        // 3) Call de Core API
        $coreRes = $api->shipmentsCapabilitiesPost($coreReq);

        // 4) Map terug naar jouw stabiele SDK-response
        return $this->mapper->mapFromCoreApi($coreRes);
    }
}
