<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\TrunkrsConsignment;

class CarrierTrunkrs extends AbstractCarrier
{
    public const CONSIGNMENT = TrunkrsConsignment::class;
    public const HUMAN       = 'Trunkrs';
    public const ID          = 16;
    public const NAME        = 'trunkrs';

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
