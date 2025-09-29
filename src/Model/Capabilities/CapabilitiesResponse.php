<?php

namespace MyParcelNL\Sdk\Model\Capabilities;

class CapabilitiesResponse
{
    public function __construct(
        array $packageTypes = [],
        array $deliveryTypes = [],
        array $shipmentOptions = []
    ) {
        $this->packageTypes = $packageTypes;
        $this->deliveryTypes = $deliveryTypes;
        $this->shipmentOptions = $shipmentOptions;
    }

    public array $packageTypes;
    public array $deliveryTypes;
    public array $shipmentOptions;
}
