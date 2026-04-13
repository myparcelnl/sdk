<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

/**
 * @internal Legacy carrier model — used by web services and Order v1 (fulfilment).
 *           Do not use in new code. Use the generated client models instead.
 */
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
