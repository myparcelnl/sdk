<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

class CarrierInPost extends AbstractCarrier
{
    public const HUMAN = 'InPost';
    public const ID    = 17;
    public const NAME  = 'inpost';

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
