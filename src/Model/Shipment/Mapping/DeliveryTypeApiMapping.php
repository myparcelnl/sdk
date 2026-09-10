<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;

/**
 * Converts delivery type values between v2 names and v1 IDs.
 * Conversion methods throw InvalidArgumentException when a value has no mapping.
 *
 * @deprecated Use {@see \MyParcelNL\Sdk\Services\Mapping\ApiMapperService::forDeliveryType()},
 *             which returns null for unknown values.
 */
final class DeliveryTypeApiMapping implements ApiMappingInterface
{
    public function enumToId(string $value): int
    {
        $id = ApiMapperService::forDeliveryType()->idFromV2Name($value);

        if (null === $id) {
            throw new InvalidArgumentException("Unknown delivery type '{$value}'");
        }

        return $id;
    }

    public function idToEnum(int $id): string
    {
        $value = ApiMapperService::forDeliveryType()->v2NameFromId($id);

        if (null === $value) {
            throw new InvalidArgumentException("Unknown delivery type id '{$id}'");
        }

        return $value;
    }

    public function all(): array
    {
        return ApiMapperService::forDeliveryType()->v2ToIdMap();
    }

    public function isValid(string $value): bool
    {
        return null !== ApiMapperService::forDeliveryType()->idFromV2Name($value);
    }
}
