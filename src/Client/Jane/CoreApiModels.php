<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject;
use ReflectionNamedType;
use ReflectionObject;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Converts the models of the generated Core API client.
 *
 * A model that comes from the API, such as the shipment that ShipmentQueryService::find() returns,
 * has public properties with camelCase names, such as `postalCode`. toArray() gives the array with
 * the API field names, such as `postal_code`.
 */
final class CoreApiModels
{
    /**
     * The generated normalizer validates a model against the spec, also when it converts the model
     * to an array. This context skips that: a model that comes from the API can hold a value that
     * the SDK does not know, such as a new enum value.
     */
    private const CONTEXT = ['skip_validation' => true];

    private static ?JaneObjectNormalizer $normalizer = null;

    private static ?Serializer $serializer = null;

    /**
     * Encode a model as an array with the API field names, such as `postal_code`.
     *
     * @return array<string, mixed> The fields that hold a value. A field that the API did not send,
     *                              or sent as null for a typed field, is left out.
     */
    public static function toArray(object $model): array
    {
        return (array) self::arrays(self::serializer()->normalize(self::withoutNullLists($model), 'json', self::CONTEXT));
    }

    /**
     * The generated normalizer returns a model as a JsonObject. Convert it and the nested models to
     * arrays.
     */
    private static function arrays(mixed $value): mixed
    {
        if ($value instanceof JsonObject) {
            $value = $value->toArray();
        }

        return is_array($value) ? array_map([self::class, 'arrays'], $value) : $value;
    }

    private static function serializer(): Serializer
    {
        return self::$serializer ??= new Serializer([new ArrayDenormalizer(), self::normalizer()]);
    }

    private static function normalizer(): JaneObjectNormalizer
    {
        return self::$normalizer ??= new JaneObjectNormalizer();
    }

    /**
     * Return a copy of the model without the lists that are null, in the model and in its nested models.
     *
     * Jane reads a nullable list that the API sends as null into a null property, but the generated
     * normalizer iterates over it without a null check, and PHP warns. The copy leaves such a property
     * unset, so the normalizer skips it.
     *
     * @todo Remove this method when a Jane release normalizes a nullable list that is null, and the
     *       client is generated again.
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

    /**
     * A generated model is an object that the generated normalizer knows. An enum is not.
     */
    private static function isModel(mixed $value): bool
    {
        return self::normalizer()->supportsNormalization($value);
    }
}
