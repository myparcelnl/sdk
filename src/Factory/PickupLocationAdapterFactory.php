<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory;

use BadMethodCallException;
use Exception;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractPickupLocationAdapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\PickupLocationV2Adapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\PickupLocationV3Adapter;

class PickupLocationAdapterFactory
{
    /**
     * @param string $checkoutData
     *
     * @return AbstractPickupLocationAdapter
     * @throws Exception
     */
    public static function create(string $checkoutData): AbstractPickupLocationAdapter
    {
        $checkoutData = json_decode($checkoutData);
        if (! is_array($checkoutData)) {
            throw new BadMethodCallException("Invalid checkout data to create PickupLocation");
        }

        if (key_exists('location', $checkoutData)) {
            return new PickupLocationV2Adapter($checkoutData);
        } elseif (key_exists('pickupLocation', $checkoutData)) {
            return new PickupLocationV3Adapter($checkoutData);
        }

        throw new BadMethodCallException("Can't create PickupLocation. No suitable adapter found");
    }
}
