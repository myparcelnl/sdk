<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

interface ApiMappingInterface
{
    /**
     * Given a v2 enum-case value, return the corresponding integer id (v1 shipment domain).
     */
    public function enumToId(string $value): int;

    /**
     * Given a v1 shipment id, return the corresponding v2 enum-case value.
     */
    public function idToEnum(int $id): string;

    /**
     * Returns the full mapping where key = v2 enum-case value and value = v1 shipment id.
     *
     * @return array<string, string>
     */
    public function all(): array;

    /**
     * Check whether the given value exists on the left-hand side (v2 enum-case side) of the map.
     */
    public function isValid(string $value): bool;
}
