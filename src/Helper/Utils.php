<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Support\Str;

class Utils
{
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
}
