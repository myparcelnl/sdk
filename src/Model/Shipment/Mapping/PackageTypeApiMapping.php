<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;

final class PackageTypeApiMapping implements ApiMappingInterface
{
    public function enumToApiRef(string $value): string
    {
        $map = $this->map();

        if (! isset($map[$value])) {
            throw new InvalidArgumentException("Unknown package type '{$value}'");
        }

        return $map[$value];
    }

    public function enumToId(string $value): int
    {
        return (int) $this->enumToApiRef($value);
    }

    public function idToEnum(int $id): string
    {
        $apiRef = (string) $id;
        $map = $this->map();
        $enum = array_search($apiRef, $map, true);

        if (false === $enum) {
            throw new InvalidArgumentException("Unknown package type id '{$id}'");
        }

        return $enum;
    }

    public function all(): array
    {
        return array_keys($this->map());
    }

    public function isValid(string $value): bool
    {
        return isset($this->map()[$value]);
    }

    /**
     * v2 enum names are the public-facing enum values.
     * v1 package type ids remain required for shipment create payloads.
     *
     * @return array<string, string>
     */
    private function map(): array
    {
        return [
            RefShipmentPackageTypeV2::PACKAGE => RefShipmentPackageType::_1,
            RefShipmentPackageTypeV2::MAILBOX => RefShipmentPackageType::_2,
            RefShipmentPackageTypeV2::UNFRANKED => RefShipmentPackageType::_3,
            RefShipmentPackageTypeV2::DIGITAL_STAMP => RefShipmentPackageType::_4,
            RefShipmentPackageTypeV2::PALLET => RefShipmentPackageType::_5,
            RefShipmentPackageTypeV2::SMALL_PACKAGE => RefShipmentPackageType::_6,
            RefShipmentPackageTypeV2::ENVELOPE => RefShipmentPackageType::_7,
        ];
    }
}

