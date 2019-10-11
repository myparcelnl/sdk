<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory;

use BadMethodCallException;
use Exception;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsV2Adapter;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsV3Adapter;
use MyParcelNL\Sdk\src\Support\Arr;

class DeliveryOptionsAdapterFactory
{
    /**
     * @param string $deliveryOptionsData
     *
     * @return AbstractDeliveryOptionsAdapter
     * @throws Exception
     */
    public static function create(string $deliveryOptionsData): AbstractDeliveryOptionsAdapter
    {
        $deliveryOptionsData = json_decode($deliveryOptionsData);

        if (! is_array($deliveryOptionsData) && ! is_object($deliveryOptionsData)) {
            throw new BadMethodCallException("Invalid data to create DeliveryOptions");
        }

        $deliveryOptionsData = Arr::fromObject($deliveryOptionsData);

        if (key_exists('price_comment', $deliveryOptionsData)) {
            return new DeliveryOptionsV2Adapter($deliveryOptionsData);
        } elseif (key_exists('carrier', $deliveryOptionsData)) {
            return new DeliveryOptionsV3Adapter($deliveryOptionsData);
        }

        throw new BadMethodCallException("Can't create DeliveryOptions. No suitable adapter found");
    }
}
