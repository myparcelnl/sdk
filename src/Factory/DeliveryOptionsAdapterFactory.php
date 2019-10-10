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

use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsV2Adapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsV3Adapter;
use MyParcelNL\Sdk\src\Support\Arr;

class DeliveryOptionsAdapterFactory
{
    /**
     * @param string $checkoutData
     *
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter
     * @throws \Exception
     */
    public static function create(string $checkoutData): AbstractDeliveryOptionsAdapter
    {
        $checkoutData = json_decode($checkoutData);

        if (! is_array($checkoutData) && ! is_object($checkoutData)) {
            throw new \BadMethodCallException("Invalid checkout data to create DeliveryOptions");
        }

        $checkoutData = Arr::fromObject($checkoutData);

        if (key_exists('price_comment', $checkoutData)) {
            return new DeliveryOptionsV2Adapter($checkoutData);
        } elseif (key_exists('deliveryType', $checkoutData)) {
            return new DeliveryOptionsV3Adapter($checkoutData);
        }

        throw new \BadMethodCallException("Can't create a DeliveryOptionsAdapter not found");
    }
}
