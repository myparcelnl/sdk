<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

class CarrierDPD extends AbstractCarrier
{
    public const HUMAN = 'DPD';
    public const ID    = 4;
    public const NAME  = 'dpd';

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
