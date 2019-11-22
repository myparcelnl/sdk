<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

class ShipmentOptionsV3Adapter extends AbstractShipmentOptionsAdapter
{
    const DEFAULT_INSURANCE = 0;
    /**
     * @param array $shipmentOptions
     */
    public function __construct(array $shipmentOptions)
    {
        $this->signature      = $shipmentOptions["signature"] ?? null;
        $this->only_recipient = $shipmentOptions["only_recipient"] ?? null;
        $this->insurance      = $shipmentOptions["insurance"] ?? self::DEFAULT_INSURANCE;
    }
}
