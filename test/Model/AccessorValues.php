<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model;

use LogicException;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use UnitEnum;

/**
 * Makes a test value that two PHP types both accept, such as the type of a Jane property and the
 * return type of the getter that reads it.
 */
final class AccessorValues
{
    /**
     * A type that is missing or `mixed` accepts every value.
     *
     * @param int $seed Makes a scalar value differ per property, so a getter that reads another property fails.
     *
     * @throws LogicException When the two types have no type in common.
     */
    public static function acceptedByBoth(?ReflectionType $first, ?ReflectionType $second, int $seed): mixed
    {
        $first  = self::names($first);
        $second = self::names($second);
        $names  = null === $first || null === $second
            ? ($first ?? $second ?? ['string'])
            : array_values(array_intersect($first, $second));

        foreach (['int', 'string', 'float', 'bool', 'array'] as $builtin) {
            if (in_array($builtin, $names, true)) {
                return match ($builtin) {
                    'int'    => $seed,
                    'string' => "value-{$seed}",
                    'float'  => $seed + 0.5,
                    'bool'   => true,
                    'array'  => [],
                };
            }
        }

        $class = $names[0] ?? throw new LogicException('The two types have no type in common.');

        return is_subclass_of($class, UnitEnum::class) ? $class::cases()[0] : new $class();
    }

    /**
     * @return list<string>|null The type names without `null`, or null for a missing or `mixed` type.
     */
    private static function names(?ReflectionType $type): ?array
    {
        $types = match (true) {
            $type instanceof ReflectionNamedType => [$type],
            $type instanceof ReflectionUnionType => $type->getTypes(),
            default                              => [],
        };

        $names = array_values(array_diff(
            array_map(static fn (ReflectionNamedType $named): string => $named->getName(), $types),
            ['null', 'mixed']
        ));

        return [] === $names ? null : $names;
    }
}
