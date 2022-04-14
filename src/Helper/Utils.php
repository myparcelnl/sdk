<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Support\Str;

class Utils
{
    /**
     * @param  string $property
     * @param  string $type
     *
     * @return string
     */
    public static function createMethodName(string $property, string $type = 'get'): string
    {
        return Str::camel("{$type}_{$property}");
    }

    /**
     * @param        $object
     * @param  array $properties
     */
    public static function fillObject($object, array $properties): void
    {
        $setters = self::getClassMethods($object, 'set');

        foreach ($properties as $key => $value) {
            $setter = self::createMethodName($key, 'set');

            if (in_array($setter, $setters)) {
                $object->{$setter}($value);
            }
        }
    }

    /**
     * @param         $object
     * @param  string $type
     *
     * @return string[]
     */
    public static function getClassMethods($object, string $type = 'get'): array
    {
        $methods = get_class_methods($object);

        return array_filter($methods, static function (string $methodName) use ($type) {
            return Str::startsWith($methodName, $type);
        });
    }
}
