<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;

/**
 * Converts package type values between v2 names and v1 IDs.
 * Conversion methods throw InvalidArgumentException when a value has no mapping.
 *
 * @deprecated Use {@see \MyParcelNL\Sdk\Services\Mapping\ApiMapperService::forPackageType()},
 *             which returns null for unknown values.
 */
final class PackageTypeApiMapping implements ApiMappingInterface
{
    public function enumToId(string $value): int
    {
        $id = ApiMapperService::forPackageType()->idFromV2Name($value);

        if (null === $id) {
            throw new InvalidArgumentException("Unknown package type '{$value}'");
        }

        return $id;
    }

    public function idToEnum(int $id): string
    {
        $value = ApiMapperService::forPackageType()->v2NameFromId($id);

        if (null === $value) {
            throw new InvalidArgumentException("Unknown package type id '{$id}'");
        }

        return $value;
    }

    public function all(): array
    {
        return ApiMapperService::forPackageType()->v2ToIdMap();
    }

    public function isValid(string $value): bool
    {
        return null !== ApiMapperService::forPackageType()->idFromV2Name($value);
    }
}
