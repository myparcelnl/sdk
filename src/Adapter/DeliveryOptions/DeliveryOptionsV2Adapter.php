<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;

/**
 * Class DeliveryOptions
 *
 * @package MyParcelNL\Sdk\src\Model\DeliveryOptions
 */
class DeliveryOptionsV2Adapter extends AbstractDeliveryOptionsAdapter
{
    /**
     * Default values to use if there is no input.
     */
    public const DEFAULTS = [
        "carrier"         => BpostConsignment::CARRIER_NAME,
        "deliveryType"    => "standard",
        "date"            => "",
        "shipmentOptions" => [],
        "isPickup"        => false,
    ];

    /**
     * @param array $deliveryOptions
     *
     * @throws Exception
     */
    public function __construct(array $deliveryOptions = [])
    {
        if (! count($deliveryOptions)) {
            $deliveryOptions = self::DEFAULTS;
        }

        $this->carrier         = $deliveryOptions["carrier"] ?? BpostConsignment::CARRIER_NAME;
        $this->date            = $deliveryOptions["date"];
        $this->deliveryType    = $deliveryOptions["deliveryType"];
        $this->shipmentOptions = new ShipmentOptionsV2Adapter($deliveryOptions["options"] ?? []);

        if ($this->isPickup()) {
            $this->pickupLocation = new PickupLocationV2Adapter($deliveryOptions["location"]);
        }
    }
}
