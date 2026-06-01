<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

use MyParcelNL\Sdk\Model\Consignment\PosteItalianeConsignment;

class CarrierPosteItaliane extends AbstractCarrier
{
    public const CONSIGNMENT = PosteItalianeConsignment::class;
    public const HUMAN       = 'Poste Italiane';
    public const ID          = 18;
    public const NAME        = 'posteitaliane';

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
