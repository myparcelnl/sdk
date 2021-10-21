<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

class CarrierPostNL extends AbstractCarrier
{
    public const HUMAN = 'PostNL';
    public const ID    = 1;
    public const NAME  = 'postnl';

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
