<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierPosteItaliane extends AbstractCarrier
{
    public const HUMAN = 'Poste Italiane';
    public const ID    = 18;
    public const NAME  = 'posteitaliane';

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
