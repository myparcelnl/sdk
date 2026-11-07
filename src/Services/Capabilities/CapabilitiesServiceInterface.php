<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;

/**
 * Checks which carriers, package types, delivery types and options the API offers for a shipment.
 */
interface CapabilitiesServiceInterface
{
    /**
     * Get the capabilities for a request with the generated request model.
     */
    public function get(CapabilitiesPostCapabilitiesRequestV2 $request): CapabilitiesResponse;

    /**
     * Get the capabilities for a shipment, from its recipient country and physical properties.
     */
    public function fromShipment(ShipmentItem $shipment): CapabilitiesResponse;
}
