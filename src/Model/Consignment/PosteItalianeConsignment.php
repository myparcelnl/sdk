<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPosteItaliane;

class PosteItalianeConsignment extends BaseConsignment
{
    /**
     * @var string
     */
    protected $carrierClass = CarrierPosteItaliane::class;
}
