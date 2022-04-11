<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class DeliveryOptionsV2Adapter extends AbstractDeliveryOptionsAdapter
{
    /**
     * Default values to use if there is no input.
     */
    public const DEFAULTS = [
        "carrier"         => null,
        "deliveryType"    => "standard",
        "date"            => "",
        "shipmentOptions" => [],
    ];

    /**
     * @param array $deliveryOptions
     */
    public function __construct(array $deliveryOptions = [])
    {
        if (! count($deliveryOptions)) {
            $deliveryOptions = self::DEFAULTS;
        }

        $this->carrier         = $deliveryOptions["carrier"] ?? null;
        $this->date            = $deliveryOptions["date"];
        $this->deliveryType    = $this->normalizeDeliveryType($deliveryOptions["time"][0]["type"]);
        $this->shipmentOptions = new ShipmentOptionsV2Adapter($deliveryOptions["options"] ?? []);

        if ($this->isPickup()) {
            $this->pickupLocation = new PickupLocationV2Adapter($deliveryOptions);
        }
    }

    /**
     * @param $deliveryType
     *
     * @return string
     */
    private function normalizeDeliveryType(int $deliveryType): string
    {
        return array_flip(AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP)[$deliveryType];
    }
}
