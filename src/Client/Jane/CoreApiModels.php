<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use ReflectionObject;
use ReflectionProperty;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use UnitEnum;

/**
 * Converts an array with the API field names, such as `postal_code`, to a Jane Core API model and back.
 *
 * The SDK models accept an array, as in v11. The generated client validates the request when it
 * goes out, so the conversion here skips the validation.
 *
 * @internal
 */
final class CoreApiModels
{
    private const CONTEXT = ['skip_validation' => true];

    private const MODEL_NAMESPACE = 'MyParcelNL\\Sdk\\Client\\Generated\\Jane\\CoreApi\\Model\\';

    private static ?Serializer $serializer = null;

    /**
     * @template T of object
     *
     * @param array<string, mixed>|T|null $value
     * @param class-string<T>             $class
     *
     * @return T|null
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
     * @return array<string, mixed> The fields that are set, with the API field names.
     */
    public static function toArray(object $model): array
    {
        return (array) self::serializer()->normalize($model, 'json', self::CONTEXT);
    }

    /**
     * Remove each property without a type that is null, in the model and in its nested models.
     *
     * Jane generates a property without a type for a field with more than one type, such as the
     * `email` of a recipient. PHP sets such a property to null, so the normalizer sends the field as
     * null, also when nobody set it. The validation then rejects the request.
     *
     * @todo Remove this method when Jane leaves out a field without a type that nobody set.
     *       `CoreApiModelsTest` fails when Jane gives the recipient email a type.
     *
     * @template T of object
     *
     * @param T $model
     *
     * @return T
     */
    public static function withoutNullUntypedProperties(object $model): object
    {
        foreach ((new ReflectionObject($model))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (! $property->isInitialized($model)) {
                continue;
            }

            $value = $model->{$property->getName()};

            if (null === $value && null === $property->getType()) {
                unset($model->{$property->getName()});

                continue;
            }

            foreach (is_array($value) ? $value : [$value] as $item) {
                if (is_object($item) && str_starts_with($item::class, self::MODEL_NAMESPACE) && ! $item instanceof UnitEnum) {
                    self::withoutNullUntypedProperties($item);
                }
            }
        }

        return $model;
    }

    private static function serializer(): Serializer
    {
        return self::$serializer ??= new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]);
    }
}
