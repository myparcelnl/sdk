<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi\Concerns;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use RuntimeException;

trait ResolvesPostShipmentsContentType
{
    /**
     * Resolve a postShipments content type by prefix instead of array index.
     *
     * This keeps the generated ShipmentApi client as source of truth while avoiding
     * fragile coupling to the order of ShipmentApi::contentTypes['postShipments'].
     */
    private function resolvePostShipmentsContentType(string $prefix): string
    {
        foreach (ShipmentApi::contentTypes['postShipments'] as $contentType) {
            if (0 === strpos($contentType, $prefix)) {
                return $contentType;
            }
        }

        throw new RuntimeException(sprintf(
            'No matching content type starting with "%s" configured in generated ShipmentApi client.',
            $prefix
        ));
    }
}
