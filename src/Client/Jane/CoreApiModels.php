<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use ReflectionNamedType;
use ReflectionObject;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use UnitEnum;

/**
 * Converts a Jane Core API model to an array with the API field names, such as `postal_code`.
 *
 * The conversion skips the validation, because the model comes from the API.
 *
 * @internal
 */
final class CoreApiModels
{
    private const CONTEXT = ['skip_validation' => true];
    private const MODEL_NAMESPACE = 'MyParcelNL\\Sdk\\Client\\Generated\\Jane\\CoreApi\\Model\\';

    private static ?Serializer $serializer = null;

    /**
     * @return array<string, mixed> The fields that are set, with the API field names. A list that the
     *                              API sent as null is left out, see withoutNullLists().
     */
    public static function toArray(object $model): array
    {
        return (array) self::serializer()->normalize(self::withoutNullLists($model), 'json', self::CONTEXT);
    }

    private static function serializer(): Serializer
    {
        return self::$serializer ??= new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]);
    }

    /**
     * Return a copy of the model without the lists that are null, in the model and in its nested models.
     *
     * Jane reads a nullable list that the API sends as null into a null property, but the generated
     * normalizer iterates over it without a null check, and PHP warns. The copy leaves such a property
     * unset, so the normalizer skips it.
     *
     * @todo Remove this method when a Jane release normalizes a nullable list that is null, and the
     *       client is generated again. `ShipmentQueryServiceTest::testJaneStillIteratesOverANullList`
     *       then fails.
     *
     * @template T of object
     *
     * @param T $model
     *
     * @return T
     */
    private static function withoutNullLists(object $model): object
    {
        $copy = clone $model;

        foreach ((new ReflectionObject($copy))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (! $property->isInitialized($copy)) {
                continue;
            }

            $name  = $property->getName();
            $value = $copy->{$name};

            if (null === $value && self::allowsArray($property->getType())) {
                unset($copy->{$name});
            } elseif (self::isModel($value)) {
                $copy->{$name} = self::withoutNullLists($value);
            } elseif (is_array($value)) {
                $copy->{$name} = array_map(static fn (mixed $item): mixed => self::isModel($item) ? self::withoutNullLists($item) : $item, $value);
            }
        }

        return $copy;
    }

    private static function allowsArray(?ReflectionType $type): bool
    {
        $types = $type instanceof ReflectionUnionType ? $type->getTypes() : [$type];

        foreach ($types as $named) {
            if ($named instanceof ReflectionNamedType && 'array' === $named->getName()) {
                return true;
            }
        }

        return false;
    }

    private static function isModel(mixed $value): bool
    {
        return is_object($value) && str_starts_with($value::class, self::MODEL_NAMESPACE) && ! $value instanceof UnitEnum;
    }
}
