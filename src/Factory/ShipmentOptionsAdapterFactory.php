<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Richard Perdaan <support@myparcel.nl>
 * @copyright   2010-2019 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v3.0.0
 */

namespace MyParcelNL\Sdk\src\Factory;

use BadMethodCallException;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractShipmentOptionsAdapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\ShipmentOptionsV2Adapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\ShipmentOptionsV3Adapter;

class ShipmentOptionsAdapterFactory
{
    /**
     * @param array $shipmentOptionsData
     *
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractShipmentOptionsAdapter
     */
    public static function create(array $shipmentOptionsData): AbstractShipmentOptionsAdapter
    {
        if (! is_array($shipmentOptionsData)) {
            throw new BadMethodCallException("Invalid checkout data to create ShipmentOptions");
        }

        if (key_exists('price_comment', $shipmentOptionsData)) {
            return new ShipmentOptionsV2Adapter($shipmentOptionsData);
        } elseif (key_exists('deliveryType', $shipmentOptionsData)) {
            return new ShipmentOptionsV3Adapter($shipmentOptionsData);
        }

        throw new BadMethodCallException("Can't create a new ShipmentOptions. No suitable adapter found");
    }
}
