<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefShipmentLocationV2
{
    /**
     * The name of the pickup location.
     *
     * @var string
     */
    public string $name;
    /**
     * The location code of the pickup location.
     *
     * @var string
     */
    public string $code;
    /**
     * Pickup / drop-off location type used for capability and rates filtering.
     *
     * @var RefShipmentLocationTypeV2|string
     */
    public RefShipmentLocationTypeV2|string $type;
}