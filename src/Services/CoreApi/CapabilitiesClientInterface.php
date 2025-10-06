<?php

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;

interface CapabilitiesClientInterface
{
    /**
     * Fetch capabilities from the Core API.
     */
    public function getCapabilities(CapabilitiesRequest $request): CapabilitiesResponse;

}
