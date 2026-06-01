<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierBRT;

class BRTConsignment extends BaseConsignment
{
    /**
     * @var string
     */
    protected $carrierClass = CarrierBRT::class;
}
