<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client;

use InvalidArgumentException;

/**
 * Converts the models of the generated API clients.
 */
final class CoreApiModels
{
    /**
     * Name suffix of the `ModelInterface` of a generated client. Each client has its own, so a `::class`
     * matches only one. The part before the suffix is the client's namespace.
     */
    private const MODEL_INTERFACE = '\\Model\\ModelInterface';

    /**
     * Encode a generated model as an array with the API field names, for example to store a response in a cache.
     *
     * Calling `json_encode()` on a generated model causes every enum to be checked and throws for a value
     * that the SDK does not know. This method keeps such a value. To get the model from the array again,
     * call `ObjectSerializer::deserialize(json_decode(json_encode($array)), $class)`.
     *
     * @param  object $model A model of a generated client.
     *
     * @return array<string, mixed>
     * @throws InvalidArgumentException For an object that is not a model of a generated client.
     */
    public static function toArray(object $model): array
    {
        $namespace = self::clientNamespace($model);

        if (null === $namespace) {
            throw new InvalidArgumentException(sprintf('%s is not a model of a generated client.', get_class($model)));
        }

        return self::encodeModel($model, $namespace);
    }

    /**
     * Encode the fields of a model the way `ObjectSerializer::sanitizeForSerialization()` does,
     * without its enum checks.
     *
     * @param  object $model
     * @param  string $namespace The namespace of the model's generated client.
     *
     * @return array<string, mixed>
     */
    private static function encodeModel(object $model, string $namespace): array
    {
        $getters = $model::getters();
        $formats = $model::openAPIFormats();
        $names   = $model::attributeMap();
        $values  = [];

        foreach ($model::openAPITypes() as $property => $type) {
            $value = $model->{$getters[$property]}();

            if (null !== $value || ($model::isNullable($property) && $model->isNullableSetToNull($property))) {
                $values[$names[$property]] = self::encode($value, $namespace, $type, $formats[$property]);
            }
        }

        return $values;
    }

    /**
     * Encode a field value. Other values than lists and models go to the client's `ObjectSerializer`.
     *
     * @param  mixed       $value
     * @param  string      $namespace The namespace of the model's generated client.
     * @param  null|string $type
     * @param  null|string $format
     *
     * @return mixed
     */
    private static function encode($value, string $namespace, ?string $type = null, ?string $format = null)
    {
        if (is_array($value)) {
            // Like the serializer, an item of a list has no type or format.
            return array_map(static function ($item) use ($namespace) {
                return self::encode($item, $namespace);
            }, $value);
        }

        $modelInterface = $namespace . self::MODEL_INTERFACE;

        if ($value instanceof $modelInterface) {
            return self::encodeModel($value, $namespace);
        }

        $serializer = $namespace . '\\ObjectSerializer';

        return $serializer::sanitizeForSerialization($value, $type, $format);
    }

    /**
     * Get the namespace of the generated client whose `ModelInterface` the object implements.
     * A hand-written subclass of a generated model is in another namespace, so the class name does not tell.
     *
     * @param  object $object
     *
     * @return null|string
     */
    private static function clientNamespace(object $object): ?string
    {
        foreach (class_implements($object) as $interface) {
            if (substr($interface, -strlen(self::MODEL_INTERFACE)) === self::MODEL_INTERFACE) {
                return substr($interface, 0, -strlen(self::MODEL_INTERFACE));
            }
        }

        return null;
    }
}
