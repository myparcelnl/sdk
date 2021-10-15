<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Carrier;

abstract class AbstractCarrier
{
    /**
     * The human-readable name of the carrier.
     *
     * @return string
     */
    abstract public static function getHuman(): string;

    /**
     * @return int
     */
    abstract public static function getId(): int;

    /**
     * @return string
     */
    abstract public static function getName(): string;
}
