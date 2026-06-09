<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierBRT extends AbstractCarrier
{
    public const HUMAN = 'BRT';
    public const ID    = 15;
    public const NAME  = 'brt';

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
