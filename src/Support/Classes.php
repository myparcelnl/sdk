<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Support;

use Exception;

class Classes
{
    /**
     * @param  mixed  $class
     * @param  string $expected
     *
     * @return mixed
     * @throws \Exception
     */
    public static function instantiateClass($class, string $expected)
    {
        if (! class_exists($class)) {
            throw new Exception("Class '$class' not found");
        }

        $validatorGroup = new $class();

        if (is_a($validatorGroup, $expected)) {
            return $validatorGroup;
        }

        throw new Exception("Class '$class' must be an instance of " . $expected);
    }
}
