<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\UPSStandardConsignment;

class CarrierUPSStandard extends AbstractCarrier
{
    public const CONSIGNMENT = UPSStandardConsignment::class;
    public const HUMAN       = 'UPS Standard';
    public const ID          = 12;
    public const NAME        = 'ups_standard';

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
