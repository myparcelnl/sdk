<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\BRTConsignment;

class CarrierBRT extends AbstractCarrier
{
    public const CONSIGNMENT = BRTConsignment::class;
    public const HUMAN       = 'BRT';
    public const ID          = 15;
    public const NAME        = 'brt';

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
