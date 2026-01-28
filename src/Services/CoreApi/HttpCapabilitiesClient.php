<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;

final class HttpCapabilitiesClient implements CapabilitiesClientInterface
{
    private CapabilitiesMapper $mapper;

    public function __construct(?CapabilitiesMapper $mapper = null)
    {
        $this->mapper = $mapper ?? new CapabilitiesMapper();
    }

    public function getCapabilities(CapabilitiesRequest $request): CapabilitiesResponse
    {

        $userAgent = sprintf(
            'MyParcelSDK/%s; PHP/%s',
            \defined('\MyParcelNL\Sdk\Sdk::VERSION') ? \MyParcelNL\Sdk\Sdk::VERSION : 'dev',
            PHP_VERSION
        );

        $api = CapabilitiesClientFactory::make(
            null,
            null,
            $userAgent,
            CapabilitiesClientFactory::ACCEPT_V2
        );
        $coreReq = $this->mapper->mapToCoreApi($request);

        $coreRes = $api->postCapabilities($coreReq);

        return $this->mapper->mapFromCoreApi($coreRes);
    }
}
