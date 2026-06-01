<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierInPost;

class InPostConsignment extends BaseConsignment
{
    /**
     * @var string
     */
    protected $carrierClass = CarrierInPost::class;
}
