<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;

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
     * Derive a capabilities request from a shipment and query it.
     * Uses the shipment's recipient country and physical properties.
     */
    public function fromShipment(ShipmentItem $shipment): CapabilitiesResponse;
}
