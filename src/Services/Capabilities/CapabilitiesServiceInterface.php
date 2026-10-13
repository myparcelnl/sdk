<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

/**
 * Check carrier capabilities (delivery types, package types, options) for a given
 * shipment configuration. Replaces the old consignment-level capability checks.
 */
interface CapabilitiesServiceInterface
{
    /**
     * Query capabilities using an explicit request object.
     */
    public function get(CapabilitiesRequest $request): CapabilitiesResponse;

    /**
     * Convenience: derive a capabilities request from a Shipment and query.
     * Uses the shipment's recipient country, carrier, and physical properties.
     */
    public function fromShipment(Shipment $shipment): CapabilitiesResponse;
}
