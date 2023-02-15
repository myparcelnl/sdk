<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\DHLEuroPlusConsignment;

class CarrierDHLEuroPlus extends AbstractCarrier
{
    public const CONSIGNMENT = DHLEuroPlusConsignment::class;
    public const HUMAN       = 'DHL Euro Plus';
    public const ID          = 11;
    public const NAME        = 'dhleuroplus';
    public const TYPE        = 'b2b';

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
