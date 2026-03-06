<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ModelInterface;

/**
 * Recursively hydrates generated OpenAPI models via their constructors.
 *
 * The generated model constructors use setIfExists() which writes directly to the
 * internal container, bypassing setter validation (including the broken preg_match
 * pattern in ShipmentDefsShipmentRecipient::setStreet()).
 *
 * Without this hydrator, nested objects would remain as plain arrays in the container,
 * causing failures when consumers call typed getters like getStatus()->getCurrent().
 *
 * @todo remove once the upstream spec/codegen fix for the street pattern is available
 *       and the client is regenerated — at that point ObjectSerializer::deserialize()
 *       can be used directly without crashing.
 */
final class ModelHydrator
{
    /**
     * Hydrate a generated model from raw array data, recursively constructing
     * all nested typed models defined in the model's openAPITypes().
     *
     * @template T of ModelInterface
     * @param class-string<T> $modelClass
     * @param array<string, mixed> $data
     * @return T
     */
    public static function hydrate(string $modelClass, array $data): ModelInterface
    {
        $types = $modelClass::openAPITypes();

        foreach ($types as $property => $type) {
            if (! isset($data[$property]) || ! is_array($data[$property])) {
                continue;
            }

            // Array of models: "SomeModel[]"
            if (self::endsWith($type, '[]')) {
                $innerType = substr($type, 0, -2);

                if (self::isModelClass($innerType)) {
                    $data[$property] = array_map(
                        static fn (array $item) => self::hydrate($innerType, $item),
                        array_filter($data[$property], 'is_array')
                    );
                }

                continue;
            }

            // Single nested model
            if (self::isModelClass($type)) {
                $data[$property] = self::hydrate($type, $data[$property]);
            }
        }

        return new $modelClass($data);
    }

    /**
     * Check if a type string represents a generated model class.
     */
    private static function isModelClass(string $type): bool
    {
        if ('' === $type || '\\' !== $type[0]) {
            return false;
        }

        return class_exists($type) && is_subclass_of($type, ModelInterface::class);
    }

    /**
     * PHP 7.4-compatible replacement for str_ends_with().
     */
    private static function endsWith(string $haystack, string $needle): bool
    {
        if ('' === $needle) {
            return true;
        }

        $needleLength = strlen($needle);

        if ($needleLength > strlen($haystack)) {
            return false;
        }

        return substr($haystack, -$needleLength) === $needle;
    }
}
