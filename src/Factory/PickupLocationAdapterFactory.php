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

use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractPickupLocationAdapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\PickupLocationV2Adapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\PickupLocationV3Adapter;

class PickupLocationAdapterFactory
{
    /**
     * @param string $checkoutData
     *
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractPickupLocationAdapter
     * @throws \Exception
     */
    public static function create(string $checkoutData): AbstractPickupLocationAdapter
    {
        $checkoutData = json_decode($checkoutData);
        if (! is_array($checkoutData)) {
            throw new \BadMethodCallException("Invalid checkout data to create PickupLocation");
        }

        if (key_exists('location', $checkoutData)) {
            return new PickupLocationV2Adapter($checkoutData);
        } elseif (key_exists('pickupLocation', $checkoutData)) {
            return new PickupLocationV3Adapter($checkoutData);
        }

        throw new \BadMethodCallException("Can't create a PickupLocationAdapter not found");
    }
}
