<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

class PickupLocationV3Adapter extends AbstractPickupLocationAdapter
{
    /**
     * @param  array $data
     */
    public function __construct(array $data)
    {
        $this->location_name     = $data["location_name"] ?? null;
        $this->location_code     = $data["location_code"] ?? null;
        $this->retail_network_id = $data["retail_network_id"] ?? null;
        $this->street            = $data["street"] ?? null;
        $this->number            = $data["number"] ?? null;
        $this->postal_code       = $data["postal_code"] ?? null;
        $this->city              = $data["city"] ?? null;
        $this->cc                = $data["cc"] ?? null;
    }
}
