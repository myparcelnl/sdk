<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

use DateTime;
use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Support\Arr;

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
        "carrier"        => BpostConsignment::CARRIER_NAME,
        "date"           => "",
        "time"           => [],
        "signature"      => false,
        "only_recipient" => false,
    ];

    /**
     * @param array $deliveryOptions
     *
     * @throws Exception
     */
    public function __construct(array $deliveryOptions = [])
    {
        $deliveryOptions = array_merge(self::DEFAULTS, $deliveryOptions);

        $this->setCarrier($deliveryOptions["carrier"]);
        $this->setDate($deliveryOptions["date"]);
        $this->setDeliveryType(Arr::get($deliveryOptions, "time.0.price_comment"));
        $this->setShipmentOptions(
            new ShipmentOptionsV2Adapter(
                [
                    "signature"      => ((bool) $deliveryOptions["signature"]) ?? null,
                    "only_recipient" => ((bool) $deliveryOptions["only_recipient"]) ?? null,
                ]
            )
        );

        if ($this->isPickup()) {
            $this->setPickupLocation(new PickupLocationV2Adapter($deliveryOptions));
        }
    }

    /**
     * Check the delivery type to see whether it's pickup or not.
     *
     * @return bool
     */
    public function isPickup(): bool
    {
        return in_array(
            $this->deliveryType,
            [
                AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME,
                AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME,
            ]
        );
    }

    public function setDeliveryType(string $deliveryType): AbstractDeliveryOptionsAdapter
    {
        switch ($deliveryType) {
            case "retail" :
                $deliveryType = AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME;
                break;
            case "retail_express" :
                $deliveryType = AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS_NAME;
                break;
        }

        $this->deliveryType = $deliveryType;
        return $this;
    }

    /**
     * @param string $date
     *
     * @return DeliveryOptionsV2Adapter
     * @throws Exception
     */
    public function setDate(string $date): AbstractDeliveryOptionsAdapter
    {
        $date = new DateTime($date);

        $this->date = $date->format(DateTime::ATOM);
        return $this;
    }
}
