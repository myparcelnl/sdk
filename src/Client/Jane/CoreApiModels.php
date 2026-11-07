<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject;
use ReflectionObject;
use ReflectionProperty;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use UnitEnum;

/**
 * Converts the models of the generated Core API client.
 *
 * A generated model has public properties with camelCase names, such as `postalCode`. toModel()
 * fills a model from an array with the API field names, such as `postal_code`, and toArray()
 * encodes a model as such an array. copy() copies a model with its nested models.
 */
final class CoreApiModels
{
    /**
     * The generated normalizer validates a model when it encodes it, as the client does for a request.
     * This context skips that: toArray() encodes a model that can be incomplete, or that came from
     * the API with a value that the SDK does not know, such as a new enum value.
     */
    private const CONTEXT = ['skip_validation' => true];

    private static ?JaneObjectNormalizer $normalizer = null;

    private static ?Serializer $serializer = null;

    /**
     * Fill a model from an array with the API field names, such as `['cc' => 'NL', 'postal_code' => '2132JE']`.
     *
     * A nested object is an array too, such as `['recipient' => ['cc' => 'NL']]`. A model that comes
     * in is returned as it is. The values are not checked here: the generated client checks the
     * request when it goes out. A key that is not an API field name is dropped without an error, also
     * the camelCase property name, such as `postalCode`: use `postal_code`.
     *
     * @template T of object
     *
     * @param array<string, mixed>|T|null $value
     * @param class-string<T>             $class
     *
     * @return T|null
     *
     * @throws InvalidArgumentException For an object of another class.
     */
    public static function toModel(array|object|null $value, string $class): ?object
    {
        if (null === $value || $value instanceof $class) {
            return $value;
        }

        if (is_array($value)) {
            return self::serializer()->denormalize($value, $class, 'json', self::CONTEXT);
        }

        throw new InvalidArgumentException(sprintf('Expected an array or %s, got %s.', $class, get_debug_type($value)));
    }

    /**
     * Fill a list of models, see toModel().
     *
     * @template T of object
     *
     * @param array<array<string, mixed>|T>|null $values
     * @param class-string<T>                    $class
     *
     * @return list<T>|null
     */
    public static function toModels(?array $values, string $class): ?array
    {
        if (null === $values) {
            return null;
        }

        return array_values(array_map(static fn (array|object $value): object => self::toModel($value, $class), $values));
    }

    /**
     * Encode a model as an array with the API field names, such as `postal_code`.
     *
     * @return array<string, mixed> The fields that hold a value, with a nested model as an array. A
     *                              field that nobody set is left out. A field that the API sent as
     *                              null is null.
     */
    public static function toArray(object $model): array
    {
        return (array) self::arrays(self::serializer()->normalize($model, 'json', self::CONTEXT));
    }

    /**
     * Copy a model with its nested models and lists, so a change to the copy does not change the
     * original. `clone` copies only the top level of a model.
     *
     * @template T of object
     *
     * @param T $model
     *
     * @return T
     */
    public static function copy(object $model): object
    {
        $copy = clone $model;

        foreach ((new ReflectionObject($copy))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isInitialized($copy)) {
                $copy->{$property->getName()} = self::copyValue($copy->{$property->getName()});
            }
        }

        return $copy;
    }

    private static function copyValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(static fn (mixed $item): mixed => self::copyValue($item), $value);
        }

        return is_object($value) && ! $value instanceof UnitEnum ? self::copy($value) : $value;
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

    /**
     * A generated model is an object that the generated normalizer knows. An enum is not.
     */
    private static function isModel(mixed $value): bool
    {
        return self::normalizer()->supportsNormalization($value);
    }
}
