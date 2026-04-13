<?php

namespace MyParcelNL\Sdk\Model\Carrier;

/**
 * @internal Legacy carrier model — used by web services and Order v1 (fulfilment).
 *           Do not use in new code. Use the generated client models instead.
 */
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
