<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use Exception;
use MyParcelNL\Sdk\src\Support\Classes;

class CarrierFactory
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier[]
     */
    public const CARRIER_CLASSES = [
        CarrierBpost::class,
        CarrierDPD::class,
        CarrierPostNL::class,
        CarrierRedJePakketje::class,
    ];

    /**
     * @param  string|int $carrierNameOrId
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function create($carrierNameOrId): AbstractCarrier
    {
        if (is_numeric($carrierNameOrId)) {
            return self::createFromId((int) $carrierNameOrId);
        }

        return self::createFromName($carrierNameOrId);
    }

    /**
     * @param  string $carrierClass
     *
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public static function createFromClass(string $carrierClass): AbstractCarrier
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
            if ($carrierId === $carrierClass::getId()) {
                return new $carrierClass();
            }
        }

        throw new Exception('No carrier found for id ' . $carrierId);
    }

    public static function canCreateFromId(int $carrierId): bool
    {
        foreach (self::CARRIER_CLASSES as $carrierClass) {
            if ($carrierId === $carrierClass::getId()) {
                return true;
            }
        }

        return false;
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
            if ($carrierName === $carrierClass::getName()) {
                return new $carrierClass();
            }
        }

        throw new Exception('No carrier found for name ' . $carrierName);
    }
}
