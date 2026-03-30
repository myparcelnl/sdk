<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierUPSStandard extends AbstractCarrier
{
    public const HUMAN = 'UPS Standard';
    public const ID    = 12;
    public const NAME  = 'upsstandard';

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
