<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;

class CarrierDPD extends AbstractCarrier
{
    /**
     * @return int
     */
    public static function getId(): int
    {
        return DPDConsignment::CARRIER_ID;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return DPDConsignment::CARRIER_NAME;
    }
}
