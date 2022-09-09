<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

use MyParcelNL\Sdk\src\Model\Consignment\DHLForYouConsignment;

class CarrierDHLForYou extends AbstractCarrier
{
    public const CONSIGNMENT = DHLForYouConsignment::class;
    public const HUMAN       = 'DHL For You';
    // TODO: Add correct carrier ID
    public const ID          = 9;
    public const NAME        = 'dhlforyou';

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
