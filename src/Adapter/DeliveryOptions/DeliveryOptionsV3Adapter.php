<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;

/**
 * Class DeliveryOptions
 *
 * @package MyParcelNL\Sdk\src\Model\DeliveryOptions
 */
class DeliveryOptionsV3Adapter extends AbstractDeliveryOptionsAdapter
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
     * DeliveryOptions constructor.
     *
     * @param array $deliveryOptions
     *
     * @throws Exception
     */
    public function __construct(array $deliveryOptions = [])
    {
        $deliveryOptions = array_merge(self::DEFAULTS, $deliveryOptions);

        $this->setCarrier($deliveryOptions["carrier"]);
        $this->setDate($deliveryOptions["date"]);
        $this->setDeliveryType($deliveryOptions["deliveryType"]);
        $this->setPickup($deliveryOptions["isPickup"]);
        $this->setShipmentOptions(
            new ShipmentOptionsV3Adapter($deliveryOptions["shipmentOptions"])
        );

        if ($this->isPickup()) {
            $this->setPickupLocation(
                new PickupLocationV3Adapter(
                    $deliveryOptions["pickupLocation"]
                )
            );
        }
    }
}
