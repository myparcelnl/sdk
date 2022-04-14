<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\InstaboxConsignment;

class CarrierInstabox extends AbstractCarrier
{
    public const CONSIGNMENT = InstaboxConsignment::class;
    public const HUMAN       = 'Instabox';
    public const ID          = 5;
    public const NAME        = 'instabox';

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

    public function isDropOffPointRequired(): bool
    {
        return true;
    }
}
