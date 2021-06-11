<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;

class CarrierBpost extends AbstractCarrier
{
    /**
     * @return int
     */
    public static function getId(): int
    {
        return BpostConsignment::CARRIER_ID;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return BpostConsignment::CARRIER_NAME;
    }
}
