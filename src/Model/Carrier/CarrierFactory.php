<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use Exception;
use MyParcelNL\Sdk\src\Support\Classes;

class CarrierFactory
{
    /**
     * @var class-string[]
     */
    public const CARRIER_CLASSES = [
        CarrierBpost::class,
        CarrierDPD::class,
        CarrierPostNL::class,
        CarrierInstabox::class,
    ];

    /**
     * @param  int $carrierId
     *
     * @return bool
     */
    public static function canCreateFromId(int $carrierId): bool
    {
        foreach (self::CARRIER_CLASSES as $carrierClass) {
            if ($carrierId === $carrierClass::ID) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string|int|\MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier $carrier
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function create($carrier): AbstractCarrier
    {
        if (is_numeric($carrier)) {
            return self::createFromId((int) $carrier);
        }

        if (is_a($carrier, AbstractCarrier::class)) {
            return $carrier;
        }

        if (is_a($carrier, AbstractCarrier::class, true)) {
            return self::createFromClass($carrier);
        }

        return self::createFromName($carrier);
    }

    /**
     * @param  string|\MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier $carrierClass
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function createFromClass($carrierClass): AbstractCarrier
    {
        return Classes::instantiateClass($carrierClass, AbstractCarrier::class);
    }

    /**
     * @param  int $carrierId
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function createFromId(int $carrierId): AbstractCarrier
    {
        foreach (self::CARRIER_CLASSES as $carrierClass) {
            if ($carrierId === $carrierClass::ID) {
                return new $carrierClass();
            }
        }

        throw new Exception('No carrier found for id ' . $carrierId);
    }

    /**
     * @param  string $carrierName
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function createFromName(string $carrierName): AbstractCarrier
    {
        foreach (self::CARRIER_CLASSES as $carrierClass) {
            if ($carrierName === $carrierClass::NAME) {
                return new $carrierClass();
            }
        }

        throw new Exception('No carrier found for name ' . $carrierName);
    }
}
