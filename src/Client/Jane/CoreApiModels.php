<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject;
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
     * The generated normalizer validates a model when it encodes it, as the client does for a request.
     * This context skips that: a model that comes from the API can hold a value that the SDK does not
     * know, such as a new enum value.
     */
    private const CONTEXT = ['skip_validation' => true];

    private static ?JaneObjectNormalizer $normalizer = null;

    private static ?Serializer $serializer = null;

    /**
     * Encode a model as an array with the API field names, such as `postal_code`.
     *
     * @return array<string, mixed> The fields that hold a value. A field that the API did not send is
     *                              left out. A field that the API sent as null is null.
     */
    public static function toArray(object $model): array
    {
        return (array) self::arrays(self::serializer()->normalize($model, 'json', self::CONTEXT));
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
     * A generated model is an object that the generated normalizer knows. An enum is not.
     */
    private static function isModel(mixed $value): bool
    {
        return self::normalizer()->supportsNormalization($value);
    }
}
