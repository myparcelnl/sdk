<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Generated\CoreApi\Model;

/**
 * Temporary override for ShipmentDefsShipment: the spec defines region as `oneOf(pattern ^[A-Z]{2}$ | enum ["Wereld"])`,
 * but generator 7.26.0 keeps only the enum, so a region like "EU" fails on write.
 *
 * @todo remove once the generator keeps the pattern branch and the client is regenerated.
 *       Also remove the typeMappings entry from openapi/coreapi.yaml.
 */
final class FixedShipment extends ShipmentDefsShipment
{
    /**
     * Get no allowed values, so region accepts every string.
     *
     * @return string[]
     */
    public function getRegionAllowableValues()
    {
        // ObjectSerializer and listInvalidProperties() skip the check when no value is allowed.
        return [];
    }
}
