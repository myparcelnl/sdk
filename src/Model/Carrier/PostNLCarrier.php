<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class PostNLCarrier extends AbstractCarrier
{
    /**
     * @return int
     */
    public static function getId(): int
    {
        return PostNLConsignment::CARRIER_ID;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return PostNLConsignment::CARRIER_NAME;
    }
}
