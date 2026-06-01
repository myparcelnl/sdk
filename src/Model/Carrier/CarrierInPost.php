<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\InPostConsignment;

class CarrierInPost extends AbstractCarrier
{
    public const CONSIGNMENT = InPostConsignment::class;
    public const HUMAN       = 'InPost';
    public const ID          = 17;
    public const NAME        = 'inpost';

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
