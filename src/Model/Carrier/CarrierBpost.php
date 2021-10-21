<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

class CarrierBpost extends AbstractCarrier
{
    public const HUMAN = 'bpost';
    public const ID    = 2;
    public const NAME  = 'bpost';

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
