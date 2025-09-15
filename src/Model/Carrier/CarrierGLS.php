<?php

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\GLSConsignment;

class CarrierGLS extends AbstractCarrier
{
    public const CONSIGNMENT = GLSConsignment::class;
    public const HUMAN       = 'GLS';
    public const ID          = 14;
    public const NAME        = 'gls';

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
