<?php

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierGLS extends AbstractCarrier
{
    public const HUMAN = 'GLS';
    public const ID    = 14;
    public const NAME  = 'gls';

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
