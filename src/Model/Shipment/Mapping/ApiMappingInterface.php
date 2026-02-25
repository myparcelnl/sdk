<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

interface ApiMappingInterface
{
    public function enumToApiRef(string $value): string;

    public function enumToId(string $value): int;

    public function idToEnum(int $id): string;

    /**
     * @return string[]
     */
    public function all(): array;

    public function isValid(string $value): bool;
}

