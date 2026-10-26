<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefShipmentPickupV2
{
    /**
     * @var RefShipmentAddressV2
     */
    public RefShipmentAddressV2 $address;
    /**
     * @var RefShipmentLocationV2
     */
    public RefShipmentLocationV2 $location;
    /**
     * PostNL Retail network ID.
     *
     * @var RefShipmentPickupV2RetailNetworkId|string
     */
    public RefShipmentPickupV2RetailNetworkId|string $retailNetworkId;
}