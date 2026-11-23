<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentDefsTrackTraceStatus
{
    /**
     * @var ShipmentDefsShipmentStatus|int
     */
    public ShipmentDefsShipmentStatus|int $current;
    /**
     * @var string|null
     */
    public ?string $main;
    /**
     * @var bool
     */
    public bool $final;
}