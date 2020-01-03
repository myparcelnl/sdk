<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

class ShipmentOptionsV3Adapter extends AbstractShipmentOptionsAdapter
{
    private const DEFAULT_INSURANCE = 0;

    /**
     * @param array $shipmentOptions
     */
    public function __construct(array $shipmentOptions)
    {
        $this->signature      = $shipmentOptions["signature"] ?? false;
        $this->only_recipient = $shipmentOptions["only_recipient"] ?? false;
        $this->insurance      = $shipmentOptions["insurance"] ?? self::DEFAULT_INSURANCE;
        $this->age_check      = $shipmentOptions["age_check"] ?? false;
        $this->large_format   = $shipmentOptions["large_format"] ?? false;
        $this->return         = $shipmentOptions["return"] ?? false;
    }
}
