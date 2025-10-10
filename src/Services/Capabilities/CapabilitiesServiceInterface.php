<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;

interface CapabilitiesServiceInterface
{
    public function get(CapabilitiesRequest $request): CapabilitiesResponse;
}
