<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;

class CarrierDPD extends AbstractCarrier
{
    public const CONSIGNMENT = DPDConsignment::class;
    public const HUMAN       = 'DPD';
    public const ID          = 4;
    public const NAME        = 'dpd';

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
