<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\UPSExpressConsignment;

class CarrierUPSExpressSaver extends AbstractCarrier
{
    public const CONSIGNMENT = UPSExpressConsignment::class;
    public const HUMAN       = 'UPS Express Saver';
    public const ID          = 13;
    public const NAME        = 'ups_express';

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
}
