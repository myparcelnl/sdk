<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierDHLEuroplus extends AbstractCarrier
{
    public const HUMAN = 'DHL Europlus';
    public const ID    = 11;
    public const NAME  = 'dhleuroplus';
    public const TYPE  = self::TYPE_B2B;

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
