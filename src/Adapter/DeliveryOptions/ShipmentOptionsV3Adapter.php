<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

class ShipmentOptionsV3Adapter extends AbstractShipmentOptionsAdapter
{
    /**
     * @param array $shipmentOptions
     */
    public function __construct(array $shipmentOptions)
    {
        $this->input          = $shipmentOptions;
        $this->signature      = $this->getOption("signature");
        $this->only_recipient = $this->getOption("only_recipient");
        $this->insurance      = $this->input["insurance"] ?? null;
    }
}
