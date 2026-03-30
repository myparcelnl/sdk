<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierDHLForYou extends AbstractCarrier
{
    public const HUMAN = 'DHL For You';
    public const ID    = 9;
    public const NAME  = 'dhlforyou';
    public const TYPE  = self::TYPE_B2C;

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

    /**
     * @var string
     */
    protected $type = self::TYPE;
}
