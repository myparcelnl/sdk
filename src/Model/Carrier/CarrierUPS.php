<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\UPSConsignment;

class CarrierUPS extends AbstractCarrier
{
    public const CONSIGNMENT = UPSConsignment::class;
    public const HUMAN       = 'UPS';
    public const ID          = 8;
    public const NAME        = 'ups';

    /**
     * @var class-string
     */
    protected $consignmentClass = self::CONSIGNMENT;

    /**
     * @var string
     */
    protected $human = self::HUMAN;

    /**
     * @var int
     */
    protected $id = self::ID;

    /**
     * @var string
     */
    protected $name = self::NAME;
}
