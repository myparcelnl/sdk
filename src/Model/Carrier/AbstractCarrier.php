<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

abstract class AbstractCarrier
{
    /**
     * @var class-string
     */
    protected $consignmentClass;

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
     * @return class-string<\MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment>
     */
    public function getConsignmentClass(): string
    {
        return $this->consignmentClass;
    }

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

    public function isDropOffPointRequired(): bool
    {
        return false;
    }
}
