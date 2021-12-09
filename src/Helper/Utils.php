<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Support\Str;

class Utils
{
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  mixed $class
     *
     * @return string
     */
    public static function classBasename($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

    /**
     * @param        $object
     * @param  array $properties
     */
    public static function fillObject($object, array $properties): void
    {
        $methods = get_class_methods($object);
        $setters = array_filter($methods, static function (string $methodName) {
            return Str::startsWith($methodName, 'set');
        });

        foreach ($properties as $key => $value) {
            $setter = Str::camel("set_$key");

            if (in_array($setter, $setters)) {
                $object->{$setter}($value);
            }
        }
    }

    /**
     * @param $class
     *
     * @return array
     */
    public static function getClassParentsRecursive($class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $nextClass) {
            $results += self::getClassTraitsRecursive($nextClass);
        }

        return array_unique($results);
    }


    /**
     * @param $value
     *
     * @return null|int
     */
    public static function intOrNull($value): ?int
    {
        if ($value) {
            return (int) $value;
        }

        return null;
    }

    public static function getClassTraitsRecursive($trait)
    {
        $traits = class_uses($trait) ?: [];

        foreach ($traits as $nextTrait) {
            $traits += self::getClassTraitsRecursive($nextTrait);
        }

        return $traits;
    }
}
