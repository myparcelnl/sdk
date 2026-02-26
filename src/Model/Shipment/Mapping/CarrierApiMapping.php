<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment\Mapping;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;

final class CarrierApiMapping implements ApiMappingInterface
{
    public function enumToId(string $value): int
    {
        $map = $this->map();

        if (! isset($map[$value])) {
            throw new InvalidArgumentException("Unknown carrier '{$value}'");
        }

        return (int) $map[$value];
    }

    public function idToEnum(int $id): string
    {
        $map = $this->map();
        $enum = array_search($id, $map, false);

        if (false === $enum) {
            throw new InvalidArgumentException("Unknown carrier id '{$id}'");
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
     * v1 carrier ids remain required for shipment create payloads.
     *
     * @return array<string, string>
     */
    private function map(): array
    {
        return [
            RefTypesCarrierV2::POSTNL => RefTypesCarrier::_1,
            RefTypesCarrierV2::BPOST => RefTypesCarrier::_2,
            RefTypesCarrierV2::CHEAP_CARGO => RefTypesCarrier::_3,
            RefTypesCarrierV2::DPD => RefTypesCarrier::_4,
            RefTypesCarrierV2::DHL_FOR_YOU => RefTypesCarrier::_9,
            RefTypesCarrierV2::DHL_PARCEL_CONNECT => RefTypesCarrier::_10,
            RefTypesCarrierV2::DHL_EUROPLUS => RefTypesCarrier::_11,
            RefTypesCarrierV2::UPS_STANDARD => RefTypesCarrier::_12,
            RefTypesCarrierV2::UPS_EXPRESS_SAVER => RefTypesCarrier::_13,
            RefTypesCarrierV2::GLS => RefTypesCarrier::_14,
            RefTypesCarrierV2::BRT => RefTypesCarrier::_15,
            RefTypesCarrierV2::TRUNKRS => RefTypesCarrier::_16,
            RefTypesCarrierV2::INPOST => RefTypesCarrier::_17,
            RefTypesCarrierV2::POSTE_ITALIANE => RefTypesCarrier::_18,
        ];
    }
}
