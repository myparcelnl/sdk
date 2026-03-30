<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierUPSExpressSaver extends AbstractCarrier
{
    public const HUMAN = 'UPS Express Saver';
    public const ID    = 13;
    public const NAME  = 'upsexpresssaver';

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
