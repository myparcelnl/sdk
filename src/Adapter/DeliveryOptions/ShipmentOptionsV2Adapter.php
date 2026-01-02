<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Adapter\DeliveryOptions;

/**
 * Class ShipmentOptionsV2Adapter
 */
class ShipmentOptionsV2Adapter extends AbstractShipmentOptionsAdapter
{
    /**
     * @param array $shipmentOptions
     */
    public function __construct(array $shipmentOptions)
    {
        $this->signature      = $shipmentOptions["signature"] ?? null;
        $this->only_recipient = $shipmentOptions["only_recipient"] ?? null;
        $this->insurance      = $shipmentOptions["insurance"] ?? null;
        $this->priority_delivery = $shipmentOptions["priority_delivery"] ?? null;
    }
}
