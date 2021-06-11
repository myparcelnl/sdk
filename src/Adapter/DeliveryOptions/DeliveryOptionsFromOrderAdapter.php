<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

class DeliveryOptionsFromOrderAdapter extends AbstractDeliveryOptionsAdapter
{
    /**
     * @param  array $data
     */
    public function __construct(array $data)
    {
        $this->carrier      = $data['carrier_id'] ?? null;
        $this->date         = $data['shipment_options']['date'] ?? null;
        $this->deliveryType = $data['shipment_options']['delivery_type'] ?? null;
        $this->packageType  = $data['shipment_options']['package_type'] ?? null;

        $this->shipmentOptions = new ShipmentOptionsV3Adapter($data['shipment_options'] ?? []);

        if ($this->isPickup()) {
            $this->pickupLocation = new PickupLocationV3Adapter($data['pickup'] ?? []);
        }
    }
}
