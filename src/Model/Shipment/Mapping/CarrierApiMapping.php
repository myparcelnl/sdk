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
     * @return array<string, int>
     */
    private function map(): array
    {
        return [
            RefTypesCarrierV2::POSTNL => RefTypesCarrier::POSTNL,
            RefTypesCarrierV2::BPOST => RefTypesCarrier::BPOST,
            RefTypesCarrierV2::CHEAP_CARGO => RefTypesCarrier::CHEAP_CARGO,
            RefTypesCarrierV2::DPD => RefTypesCarrier::DPD,
            RefTypesCarrierV2::DHL_FOR_YOU => RefTypesCarrier::DHL_FOR_YOU,
            RefTypesCarrierV2::DHL_PARCEL_CONNECT => RefTypesCarrier::DHL_PARCEL_CONNECT,
            RefTypesCarrierV2::DHL_EUROPLUS => RefTypesCarrier::DHL_EUROPLUS,
            RefTypesCarrierV2::UPS_STANDARD => RefTypesCarrier::UPS_STANDARD,
            RefTypesCarrierV2::UPS_EXPRESS_SAVER => RefTypesCarrier::UPS_EXPRESS_SAVER,
            RefTypesCarrierV2::GLS => RefTypesCarrier::GLS,
            RefTypesCarrierV2::BRT => RefTypesCarrier::BRT,
            RefTypesCarrierV2::TRUNKRS => RefTypesCarrier::TRUNKRS,
            RefTypesCarrierV2::INPOST => RefTypesCarrier::INPOST,
            RefTypesCarrierV2::POSTE_ITALIANE => RefTypesCarrier::POSTE_ITALIANE,
        ];
    }
}
