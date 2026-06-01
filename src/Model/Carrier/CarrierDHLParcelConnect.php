<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

/**
 * @internal Legacy carrier model — used by web services and Order v1 (fulfilment).
 *           Do not use in new code. Use the generated client models instead.
 */
class CarrierDHLParcelConnect extends AbstractCarrier
{
    public const HUMAN = 'DHL Parcel Connect';
    public const ID    = 10;
    public const NAME  = 'dhlparcelconnect';
    public const TYPE  = self::TYPE_B2C;

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

    /**
     * @var string
     */
    protected $type = self::TYPE;
}
