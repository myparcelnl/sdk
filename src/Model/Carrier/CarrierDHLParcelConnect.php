<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\Consignment\DHLParcelConnectConsignment;

class CarrierDHLParcelConnect extends AbstractCarrier
{
    public const CONSIGNMENT = DHLParcelConnectConsignment::class;
    public const HUMAN       = 'DHL Parcel Connect';
    public const ID          = 10;
    public const NAME        = 'dhlparcelconnect';
    public const TYPE        = AbstractConsignment::TYPE_B2C;

    /**
     * @var class-string
     */
    protected $consignmentClass = self::CONSIGNMENT;

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
