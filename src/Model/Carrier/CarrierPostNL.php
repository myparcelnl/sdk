<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class CarrierPostNL extends AbstractCarrier
{
    public const CONSIGNMENT = PostNLConsignment::class;
    public const HUMAN       = 'PostNL';
    public const ID          = 1;
    public const NAME        = 'postnl';

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
