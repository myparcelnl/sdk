<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory;

use BadMethodCallException;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractShipmentOptionsAdapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\ShipmentOptionsV3Adapter;

class ShipmentOptionsAdapterFactory
{
    /**
     * @param array $shipmentOptions
     *
     * @return AbstractShipmentOptionsAdapter
     */
    public static function create(array $shipmentOptions): AbstractShipmentOptionsAdapter
    {
        if (key_exists('signature', $shipmentOptions)) {
            return new ShipmentOptionsV3Adapter($shipmentOptions);
        }

        throw new BadMethodCallException("Can't create a new ShipmentOptions. No suitable adapter found");
    }
}
