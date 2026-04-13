<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

/**
 * @internal Legacy carrier model — used by web services and Order v1 (fulfilment).
 *           Do not use in new code. Use the generated client models instead.
 */
class CarrierDPD extends AbstractCarrier
{
    public const HUMAN = 'DPD';
    public const ID    = 4;
    public const NAME  = 'dpd';

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
