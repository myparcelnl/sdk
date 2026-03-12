<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\CoreApi;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ModelInterface;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentRecipient;
use MyParcelNL\Sdk\Client\Override\FixedShipmentRecipient;
use MyParcelNL\Sdk\Support\Str;

/**
 * Recursively hydrates generated OpenAPI models via their constructors.
 *
 * Generated model constructors use setIfExists() which writes directly to the
 * internal container. This is consistent with how the generated models work
 * internally. Without hydration, nested objects would remain as plain arrays
 * in the container, causing failures when consumers call typed getters like
 * getStatus()->getCurrent().
 *
 * Uses a type override map to substitute the buggy ShipmentDefsShipmentRecipient
 * with FixedShipmentRecipient (which corrects the broken setStreet() regex).
 *
 * @todo remove once the upstream spec/codegen fix for the street pattern is available
 *       and the client is regenerated — at that point ObjectSerializer::deserialize()
 *       can be used directly.
 */
final class ModelHydrator
{
    /**
     * Type overrides for generated models with known bugs.
     *
     * Maps fully-qualified generated class names to their fixed replacements.
     *
     * @todo remove once the street pattern is fixed in the generated client.
     *
     * @var array<string, string>
     */
    private static $typeOverrides = [
        ShipmentDefsShipmentRecipient::class => FixedShipmentRecipient::class,
    ];

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
            if (Str::endsWith($type, '[]')) {
                $innerType = self::resolveType(substr($type, 0, -2));

                if (self::isModelClass($innerType)) {
                    $data[$property] = array_map(
                        static fn (array $item) => self::hydrate($innerType, $item),
                        array_filter($data[$property], 'is_array')
                    );
                }

                continue;
            }

            // Single nested model
            $resolvedType = self::resolveType($type);

            if (self::isModelClass($resolvedType)) {
                $data[$property] = self::hydrate($resolvedType, $data[$property]);
            }
        }

        $resolvedClass = self::resolveType($modelClass);

        return new $resolvedClass($data);
    }

    /**
     * Resolve a type through the override map.
     *
     * Returns the override class if one is registered, otherwise the original.
     */
    private static function resolveType(string $type): string
    {
        $normalized = ltrim($type, '\\');

        foreach (self::$typeOverrides as $original => $replacement) {
            if ($normalized === $original || $type === '\\' . $original) {
                return $replacement;
            }
        }

        return $type;
    }

    /**
     * Check if a type string represents a generated model class.
     */
    private static function isModelClass(string $type): bool
    {
        if ('' === $type) {
            return false;
        }

        $normalized = ltrim($type, '\\');

        return class_exists($normalized) && is_subclass_of($normalized, ModelInterface::class);
    }
}
