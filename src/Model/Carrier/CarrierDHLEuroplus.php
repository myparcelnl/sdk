<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DHLEuroplusConsignment;

class CarrierDHLEuroplus extends AbstractCarrier
{
    public const CONSIGNMENT = DHLEuroplusConsignment::class;
    public const HUMAN       = 'DHL Europlus';
    public const ID          = 11;
    public const NAME        = 'dhleuroplus';
    public const TYPE        = AbstractConsignment::TYPE_B2B;

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
