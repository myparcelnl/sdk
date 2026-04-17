<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;

final class DeliveryTypeApiMapping implements ApiMappingInterface
{
    public function enumToId(string $value): int
    {
        $map = $this->map();

        if (! isset($map[$value])) {
            throw new InvalidArgumentException("Unknown delivery type '{$value}'");
        }

        return (int) $map[$value];
    }

    public function idToEnum(int $id): string
    {
        $map = $this->map();
        $enum = array_search($id, $map, false);

        if (false === $enum) {
            throw new InvalidArgumentException("Unknown delivery type id '{$id}'");
        }

        return $enum;
    }

    public function all(): array
    {
        return $this->map();
    }

    public function isValid(string $value): bool
    {
        return isset($this->map()[$value]);
    }

    /**
     * v2 enum names are the public-facing enum values.
     * v1 delivery type ids remain required for order v1 fulfilment payloads.
     *
     * @return array<string, int>
     */
    private function map(): array
    {
        return [
            RefTypesDeliveryTypeV2::MORNING       => RefTypesDeliveryType::MORNING,
            RefTypesDeliveryTypeV2::STANDARD      => RefTypesDeliveryType::STANDARD,
            RefTypesDeliveryTypeV2::EVENING       => RefTypesDeliveryType::EVENING,
            RefTypesDeliveryTypeV2::PICKUP        => RefTypesDeliveryType::PICKUP,
            RefTypesDeliveryTypeV2::SAME_DAY      => RefTypesDeliveryType::SAME_DAY,
            RefTypesDeliveryTypeV2::EXPRESS        => RefTypesDeliveryType::EXPRESS,
            RefTypesDeliveryTypeV2::EARLY_MORNING => RefTypesDeliveryType::EARLY_MORNING,
        ];
    }
}
