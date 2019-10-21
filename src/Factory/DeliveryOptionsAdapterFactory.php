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
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsV2Adapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsV3Adapter;
use MyParcelNL\Sdk\src\Support\Arr;

class DeliveryOptionsAdapterFactory
{
    /**
     * @param array $deliveryOptionsData
     *
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter
     * @throws \Exception
     */
    public static function create(array $deliveryOptionsData): AbstractDeliveryOptionsAdapter
    {
        $deliveryOptionsData = Arr::fromObject($deliveryOptionsData);

        if (key_exists('time', $deliveryOptionsData) && is_array($deliveryOptionsData["time"])) {
            return new DeliveryOptionsV2Adapter($deliveryOptionsData);
        } elseif (key_exists('deliveryType', $deliveryOptionsData)) {
            return new DeliveryOptionsV3Adapter($deliveryOptionsData);
        }

        throw new BadMethodCallException("Can't create DeliveryOptions. No suitable adapter found");
    }
}
