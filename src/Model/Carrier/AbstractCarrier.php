<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Carrier;

abstract class AbstractCarrier
{
    public const TYPE_B2C = 'b2c';
    public const TYPE_B2B = 'b2b';

    /**
     * @var string
     */
    protected $human;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * The human-readable name of the carrier.
     *
     * @return string
     */
    public function getHuman(): string
    {
        return $this->human;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
