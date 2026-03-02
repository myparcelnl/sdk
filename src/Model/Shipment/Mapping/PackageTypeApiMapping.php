<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;

final class PackageTypeApiMapping implements ApiMappingInterface
{
    public function enumToId(string $value): int
    {
        $map = $this->map();

        if (! isset($map[$value])) {
            throw new InvalidArgumentException("Unknown package type '{$value}'");
        }

        return (int) $map[$value];
    }

    public function idToEnum(int $id): string
    {
        $map = $this->map();
        $enum = array_search($id, $map, false);

        if (false === $enum) {
            throw new InvalidArgumentException("Unknown package type id '{$id}'");
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
     * v1 package type ids remain required for shipment create payloads.
     *
     * @return array<string, int>
     */
    private function map(): array
    {
        return [
            RefShipmentPackageTypeV2::PACKAGE => RefShipmentPackageType::PACKAGE,
            RefShipmentPackageTypeV2::MAILBOX => RefShipmentPackageType::MAILBOX,
            RefShipmentPackageTypeV2::UNFRANKED => RefShipmentPackageType::UNFRANKED,
            RefShipmentPackageTypeV2::DIGITAL_STAMP => RefShipmentPackageType::DIGITAL_STAMP,
            RefShipmentPackageTypeV2::PALLET => RefShipmentPackageType::PALLET,
            RefShipmentPackageTypeV2::SMALL_PACKAGE => RefShipmentPackageType::SMALL_PACKAGE,
            RefShipmentPackageTypeV2::ENVELOPE => RefShipmentPackageType::ENVELOPE,
        ];
    }
}
