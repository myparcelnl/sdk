<?php

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;

interface CapabilitiesClientInterface
{
    /**
     * Fetch capabilities from the Core API.
     */
    public function getCapabilities(CapabilitiesPostCapabilitiesRequestV2 $request): CapabilitiesResponsesCapabilitiesV2;

}
