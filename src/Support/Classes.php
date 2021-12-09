<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Support;

use Exception;
use RuntimeException;

class Classes
{
    /**
     * @param  mixed       $class
     * @param  null|string $expected
     * @param  mixed       ...$args
     *
     * @return mixed
     * @throws \Exception
     */
    public static function instantiateClass($class, ?string $expected = null, ...$args)
    {
        if (! class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        $createdClass = new $class(...$args);

        if (! $expected || is_a($createdClass, $expected)) {
            return $createdClass;
        }

        throw new Exception("Class '$class' must be an instance of " . $expected);
    }
}
