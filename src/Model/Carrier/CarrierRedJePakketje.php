<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\RedJePakketjeConsignment;

class CarrierRedJePakketje extends AbstractCarrier
{
    public const CONSIGNMENT = RedJePakketjeConsignment::class;
    public const HUMAN       = 'Red Je Pakketje';
    public const ID          = 5;
    public const NAME        = 'redjepakketje';

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
