<?php declare(strict_types=1); /** @noinspection PhpInternalEntityUsedInspection */

/**
 * This model represents one request
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v3.0.0
 */

namespace MyParcelNL\Sdk\src\Model;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;

class DeliveryOptions
{
    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $deliveryType;

    /**
     * @var object|null
     */
    private $shipmentOptions;

    /**
     * @var string
     */
    private $carrier;

    /**
     * @var PickupLocation
     */
    private $pickupLocation;

    /**
     * DeliveryOptions constructor.
     *
     * @param array $deliveryOptions
     *
     * @throws Exception
     */
    public function __construct(array $deliveryOptions)
    {
        if (array_key_exists("carrier", $deliveryOptions)) {
            $carrier = $deliveryOptions["carrier"];
        }

        $this->deliveryType    = $deliveryOptions["deliveryType"];
        $this->date            = $deliveryOptions["deliveryDate"];
        $this->shipmentOptions = $deliveryOptions["shipmentOptions"] ?? null;
        $this->carrier         = $carrier ?? BpostConsignment::CARRIER_NAME;

        if ($this->isPickup()) {
            $this->pickupLocation = new PickupLocation($deliveryOptions["pickupLocation"]);
        }
    }

    /**
     * @return string
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    /**
     * @return object|null
     */
    public function getShipmentOptions()
    {
        return $this->shipmentOptions;
    }

    /**
     * @return string
     */
    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    /**
     * @return PickupLocation
     */
    public function getPickupLocation(): ?PickupLocation
    {
        return $this->pickupLocation;
    }

    /**
     * @return bool
     */
    public function isPickup(): ?bool
    {
        return $this->deliveryType === "pickup";
    }
}
