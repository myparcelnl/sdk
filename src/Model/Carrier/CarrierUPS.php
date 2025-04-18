<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\UPSConsignment;

/**
 * @deprecated Use CarrierUPSStandard (ID: 12) or CarrierUPSExpressSaver (ID: 13) instead
 */
class CarrierUPS extends AbstractCarrier
{
    public const CONSIGNMENT = UPSConsignment::class;
    public const HUMAN       = 'UPS';
    public const ID          = 8;
    public const NAME        = 'ups';

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
