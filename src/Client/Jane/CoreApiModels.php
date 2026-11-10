<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject;
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
     * @return array<string, mixed> The fields that the model holds. Most fields that the API did not
     *                              send or sent as null are left out. A field with a default, such as
     *                              `delayed`, is in the array.
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
        return self::$serializer ??= new Serializer([self::normalizer()]);
    }

    private static function normalizer(): JaneObjectNormalizer
    {
        return self::$normalizer ??= new JaneObjectNormalizer();
    }
}
