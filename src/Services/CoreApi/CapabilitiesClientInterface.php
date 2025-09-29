<?php

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;

interface CapabilitiesClientInterface
{
    /**
     * Vraagt capabilities op bij de Core API.
     */
    public function getCapabilities(CapabilitiesRequest $request);
}
