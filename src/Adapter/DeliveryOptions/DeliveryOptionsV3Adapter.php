<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use Exception;

/**
 * Class DeliveryOptionsV3Adapter
 */
class DeliveryOptionsV3Adapter extends AbstractDeliveryOptionsAdapter
{
    /**
     * Default values to use if there is no input.
     */
    public const DEFAULTS = [
        "deliveryType"    => "standard",
        "date"            => "",
        "shipmentOptions" => [],
        "isPickup"        => false,
    ];

    /**
     * DeliveryOptions constructor.
     *
     * @param array $deliveryOptions
     */
    public function __construct(array $deliveryOptions = [])
    {
        if (! count($deliveryOptions)) {
            $deliveryOptions = self::DEFAULTS;
        }

        $this->carrier         = $deliveryOptions["carrier"] ?? null;
        $this->date            = $deliveryOptions["date"] ?? null;
        $this->deliveryType    = $deliveryOptions["deliveryType"] ?? null;
        $this->packageType     = $deliveryOptions["packageType"] ?? null;
        $this->shipmentOptions = new ShipmentOptionsV3Adapter($deliveryOptions["shipmentOptions"] ?? []);

        if ($this->isPickup()) {
            $this->pickupLocation = new PickupLocationV3Adapter($deliveryOptions["pickupLocation"]);
        }
    }
}
