<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierTrunkrs extends AbstractCarrier
{
    public const HUMAN = 'Trunkrs';
    public const ID    = 16;
    public const NAME  = 'trunkrs';

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
