<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use BackedEnum;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2 as CoreRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2 as CoreResponseV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV2 as CoreCapabilityV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;
use MyParcelNL\Sdk\Support\Str;

/**
 * Maps a CapabilitiesRequest to the Jane request model, and the Jane response model to a CapabilitiesResponse.
 *
 * The v2 capabilities API uses camelCase field names, such as `physicalProperties`. A
 * CapabilitiesRequest takes snake_case keys, such as `is_business`, as in v11.
 */
class CapabilitiesMapper
{
    private const PHYSICAL_PROPERTIES = ['height', 'width', 'length', 'weight'];

    /**
     * Map SDK request to the Core API request model.
     *
     * Note: we intentionally do not filter enum-like values (carrier, deliveryType, packageType, direction)
     * to preserve forward compatibility when the API adds new allowable values.
     */
    public function mapToCoreApi(CapabilitiesRequest $request): CoreRequestV2
    {
        $data = array_filter([
            'shopId'             => $request->getShopId(),
            'recipient'          => ['countryCode' => $request->getCountryCode()],
            'carrier'            => $request->getCarrier(),
            'deliveryType'       => $request->getDeliveryType(),
            'packageType'        => $request->getPackageType(),
            'direction'          => $request->getDirection(),
            'sender'             => $this->mapSender($request->getSender()),
            'options'            => $this->mapOptions($request->getOptions()),
            'physicalProperties' => $this->mapPhysicalProperties($request->getPhysicalProperties()),
            'pickup'             => $this->mapPickup($request->getPickup()),
        ], static fn($value): bool => null !== $value);

        return CoreApiModels::withoutNullUntypedProperties(CoreApiModels::toModel($data, CoreRequestV2::class));
    }

    /**
     * Map Core API response to the SDK response model.
     *
     * Note: physical properties are treated as input-only for this SDK response.
     */
    public function mapFromCoreApi(CoreResponseV2 $core): CapabilitiesResponse
    {
        $results = $core->results ?? [];

        if (empty($results)) {
            return new CapabilitiesResponse([], [], [], null, [], null);
        }

        $packageTypes = [];
        $deliveryTypes = [];
        $optionKeys = [];
        $transactionTypes = [];
        $carrier = null;
        $carrierInconsistent = false;
        $colloMax = null;

        foreach ($results as $res) {
            $packageTypes = array_values(array_unique(array_merge(
                $packageTypes,
                self::values($res->packageTypes ?? [])
            )));

            $deliveryTypes = array_values(array_unique(array_merge(
                $deliveryTypes,
                self::values($res->deliveryTypes ?? [])
            )));

            $optionKeys = array_values(array_unique(array_merge(
                $optionKeys,
                $this->optionNames($res)
            )));

            if (! $carrierInconsistent) {
                $resCarrier = isset($res->carrier) ? self::value($res->carrier) : null;
                if (null === $carrier) {
                    $carrier = $resCarrier;
                } elseif ($carrier !== $resCarrier) {
                    $carrier = null;
                    $carrierInconsistent = true;
                }
            }

            $transactionTypes = array_values(array_unique(array_merge(
                $transactionTypes,
                self::values($res->transactionTypes ?? [])
            )));

            if (isset($res->collo->max)) {
                $colloMax = max($colloMax ?? 0, $res->collo->max);
            }
        }

        return new CapabilitiesResponse(
            $packageTypes,
            $deliveryTypes,
            $optionKeys,
            $carrier,
            $transactionTypes,
            $colloMax
        );
    }

    /**
     * The API names of the shipment options that the result allows, such as `requiresSignature`.
     *
     * The Jane model sets only the options that the API sent.
     *
     * @return string[]
     */
    private function optionNames(CoreCapabilityV2 $result): array
    {
        if (! isset($result->options)) {
            return [];
        }

        $names = [];

        foreach ($result->options->definedProperties() as $property => $apiName) {
            if (isset($result->options->{$property})) {
                $names[] = $apiName;
            }
        }

        return $names;
    }

    /**
     * @param array<string, mixed>|null $senderData
     *
     * @return array<string, mixed>|null
     */
    private function mapSender(?array $senderData): ?array
    {
        if (! $senderData) {
            return null;
        }

        $sender = [];

        if (array_key_exists('country_code', $senderData)) {
            $sender['countryCode'] = $senderData['country_code'];
        }

        if (array_key_exists('is_business', $senderData)) {
            $sender['isBusiness'] = $senderData['is_business'];
        }

        return $sender;
    }

    /**
     * Map supported options to the capabilities v2 request.
     * Call CapabilitiesRequest::getUnsupportedOptions() before sending to handle omitted options.
     *
     * @param array<string, mixed>|null $optionsData
     *
     * @return array<string, mixed>|null
     */
    private function mapOptions(?array $optionsData): ?array
    {
        if (! $optionsData) {
            return null;
        }

        $options = [];
        $mapper  = new ShipmentOptionMapper();

        foreach ($optionsData as $key => $value) {
            $property = $mapper->v2PropertyFromName((string) $key);

            if (null === $property) {
                continue;
            }

            $options[Str::camel($property)] = $this->normalizeOptionValue($value);
        }

        return $options;
    }

    /**
     * In the Capabilities API, null typically represents an enabled option without configuration.
     *
     * Important: do not use truthy checks, as false/0 may be valid values.
     *
     * @param mixed $value
     * @return mixed
     */
    private function normalizeOptionValue($value)
    {
        return $value === null ? new \stdClass() : $value;
    }

    /**
     * Keep each dimension that has a value and a unit. The spec types the value as an integer.
     *
     * @param array<string, mixed>|null $physicalData
     *
     * @return array<string, array{value: mixed, unit: mixed}>|null
     */
    private function mapPhysicalProperties(?array $physicalData): ?array
    {
        if (! $physicalData) {
            return null;
        }

        $physical = [];

        foreach (self::PHYSICAL_PROPERTIES as $name) {
            $dimension = $physicalData[$name] ?? null;

            if (is_array($dimension) && array_key_exists('value', $dimension) && array_key_exists('unit', $dimension)) {
                $physical[$name] = ['value' => $dimension['value'], 'unit' => $dimension['unit']];
            }
        }

        return $physical;
    }

    /**
     * @param array<string, mixed>|null $pickupData
     *
     * @return array<string, mixed>|null
     */
    private function mapPickup(?array $pickupData): ?array
    {
        if (! $pickupData) {
            return null;
        }

        $pickup = [];

        if (isset($pickupData['location']) && is_array($pickupData['location'])) {
            $pickup['location'] = array_key_exists('type', $pickupData['location'])
                ? ['type' => $pickupData['location']['type']]
                : [];
        }

        return $pickup;
    }

    /**
     * The API value of each item. Jane returns an enum case for a value it knows, and the raw string otherwise.
     *
     * @param array<BackedEnum|string> $items
     *
     * @return string[]
     */
    private static function values(array $items): array
    {
        return array_map([self::class, 'value'], $items);
    }

    private static function value(BackedEnum|string $item): string
    {
        return $item instanceof BackedEnum ? (string) $item->value : $item;
    }
}
