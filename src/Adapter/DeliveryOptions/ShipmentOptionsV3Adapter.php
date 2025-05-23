<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Adapter\DeliveryOptions;

class ShipmentOptionsV3Adapter extends AbstractShipmentOptionsAdapter
{
    /**
     * @param array $shipmentOptions
     */
    public function __construct(array $shipmentOptions)
    {
        $this->age_check         = $shipmentOptions['age_check'] ?? null;
        $this->extra_assurance   = $shipmentOptions['extra_assurance'] ?? null;
        $this->hide_sender       = $shipmentOptions['hide_sender'] ?? null;
        $this->insurance         = $shipmentOptions['insurance'] ?? null;
        $this->label_description = $shipmentOptions['label_description'] ?? null;
        $this->large_format      = $shipmentOptions['large_format'] ?? null;
        $this->only_recipient    = $shipmentOptions['only_recipient'] ?? null;
        $this->return            = $shipmentOptions['return'] ?? null;
        $this->same_day_delivery = $shipmentOptions['same_day_delivery'] ?? null;
        $this->signature         = $shipmentOptions['signature'] ?? null;
        $this->collect           = $shipmentOptions['collect'] ?? null;
        $this->receipt_code      = $shipmentOptions['receipt_code'] ?? null;
    }
}
