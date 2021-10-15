<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\RedJePakketjeConsignment;

class CarrierRedJePakketje extends AbstractCarrier
{
    /**
     * @return int
     */
    public static function getId(): int
    {
        return RedJePakketjeConsignment::CARRIER_ID;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return RedJePakketjeConsignment::CARRIER_NAME;
    }

    /**
     * @return string
     */
    public static function getHuman(): string
    {
        return 'Red Je Pakketje';
    }
}
