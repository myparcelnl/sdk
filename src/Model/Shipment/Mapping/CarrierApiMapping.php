<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;

/**
 * Preserves the existing v2-to-id API and its exceptions for unknown values.
 *
 * @deprecated Use {@see \MyParcelNL\Sdk\Services\Mapping\ApiMapperService::forCarrier()},
 *             which returns null for unknown values.
 */
final class CarrierApiMapping implements ApiMappingInterface
{
    public function enumToId(string $value): int
    {
        $id = ApiMapperService::forCarrier()->idFromV2Name($value);

        if (null === $id) {
            throw new InvalidArgumentException("Unknown carrier '{$value}'");
        }

        return $id;
    }

    public function idToEnum(int $id): string
    {
        $value = ApiMapperService::forCarrier()->v2NameFromId($id);

        if (null === $value) {
            throw new InvalidArgumentException("Unknown carrier id '{$id}'");
        }

        return $value;
    }

    public function all(): array
    {
        return ApiMapperService::forCarrier()->v2ToIdMap();
    }

    public function isValid(string $value): bool
    {
        return null !== ApiMapperService::forCarrier()->idFromV2Name($value);
    }
}
