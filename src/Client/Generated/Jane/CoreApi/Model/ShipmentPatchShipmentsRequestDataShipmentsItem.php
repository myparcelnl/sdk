<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPatchShipmentsRequestDataShipmentsItem
{
    /**
     * @var int
     */
    public int $id;
    /**
     * @var ShipmentPatchShipmentsRequestDataShipmentsItemHidden|int
     */
    public ShipmentPatchShipmentsRequestDataShipmentsItemHidden|int $hidden;
    /**
     * @var ShipmentDefsShipmentStatus|int
     */
    public ShipmentDefsShipmentStatus|int $status;
    /**
     * @var ShipmentPatchShipmentsRequestDataShipmentsItemDelivered|int
     */
    public ShipmentPatchShipmentsRequestDataShipmentsItemDelivered|int $delivered;
}