<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

abstract class AbstractCarrier
{
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
}
