<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

/**
 * @internal Legacy carrier model — used by web services and Order v1 (fulfilment).
 *           Do not use in new code. Use the generated client models instead.
 */
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
