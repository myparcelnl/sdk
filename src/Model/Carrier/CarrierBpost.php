<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;

class CarrierBpost extends AbstractCarrier
{
    public const CONSIGNMENT = BpostConsignment::class;
    public const HUMAN       = 'bpost';
    public const ID          = 2;
    public const NAME        = 'bpost';

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
