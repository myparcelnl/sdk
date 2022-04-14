<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns;

trait HasInstance
{
    /**
     * @var static
     */
    protected static $instance;

    /**
     * @param  mixed ...$arguments
     *
     * @return static
     * @TODO         add return type "static" when we've dropped support for php < 8
     * @noinspection PhpMissingReturnTypeInspection
     */
    public static function getInstance(...$arguments)
    {
        if (! static::$instance) {
            static::$instance = (new static(...$arguments));
        }

        return static::$instance;
    }
}
