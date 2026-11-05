<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use BackedEnum;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2 as CoreRequestV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2 as CoreResponseV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV2 as CoreCapabilityV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;
use MyParcelNL\Sdk\Support\Str;
use ReflectionClass;
use ReflectionProperty;

/**
 * Maps a CapabilitiesRequest to the Jane request model, and the Jane response model to a CapabilitiesResponse.
 *
 * The v2 capabilities API uses camelCase field names, such as `physicalProperties`. A
 * CapabilitiesRequest takes the keys of the SDK, such as `is_business` or `isBusiness`. The mapper
 * converts each key of `sender`, `physical_properties` and `pickup` to the camelCase name, so the Jane
 * model owns the field list.
 */
class CapabilitiesMapper
{
    /**
     * Map SDK request to the Core API request model.
     *
     * The mapper passes `carrier`, `deliveryType`, `packageType` and `direction` as they are. The generated
     * client checks them against the spec before it sends the request, and throws a ValidationException
     * for a value that the spec does not know.
     *
     * @throws InvalidArgumentException For a physical property that is not a whole number.
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

        // toArray() gives the API names of the set options, also an option that the spec does not know yet.
        return array_keys($result->options->toArray());
    }

    /**
     * Convert the keys of the sender to the camelCase names of the Jane model, such as `postalCode`.
     *
     * @param array<string, mixed>|null $senderData
     *
     * @return array<string, mixed>|null
     */
    private function mapSender(?array $senderData): ?array
    {
        if (! $senderData) {
            return null;
        }

        return self::camelKeys($senderData);
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

            $options[$property] = $this->normalizeOptionValue($value);
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
     * Keep each dimension that has a value and a unit. The spec types the value as an integer: the
     * generated normalizer assigns the value to an `int` property, and PHP truncates a float or a
     * numeric string such as `10.5`. So a value that is not whole throws here.
     *
     * @param array<string, mixed>|null $physicalData
     *
     * @return array<string, array{value: mixed, unit: mixed}>|null
     *
     * @throws InvalidArgumentException For a value that is not a whole number, such as 10.5.
     */
    private function mapPhysicalProperties(?array $physicalData): ?array
    {
        if (! $physicalData) {
            return null;
        }

        $physical = [];

        foreach (self::physicalPropertyNames() as $name) {
            $dimension = $physicalData[$name] ?? null;

            if (is_array($dimension) && array_key_exists('value', $dimension) && array_key_exists('unit', $dimension)) {
                // A numeric string passes is_numeric() too, and PHP truncates it in the same way.
                if (is_numeric($dimension['value']) && (float) $dimension['value'] !== floor((float) $dimension['value'])) {
                    throw new InvalidArgumentException(sprintf('The %s of the physical properties must be a whole number, %s given.', $name, $dimension['value']));
                }

                $physical[$name] = ['value' => $dimension['value'], 'unit' => $dimension['unit']];
            }
        }

        return $physical;
    }

    /**
     * The names of the physical properties, read from the Jane model, so a new dimension in the spec
     * needs no change here.
     *
     * @return list<string>
     */
    private static function physicalPropertyNames(): array
    {
        return array_map(
            static fn (ReflectionProperty $property): string => $property->getName(),
            (new ReflectionClass(CapabilitiesPostCapabilitiesRequestV2PhysicalProperties::class))->getProperties(ReflectionProperty::IS_PUBLIC)
        );
    }

    /**
     * Convert the keys of the pickup to the camelCase names of the Jane model, in `location` too.
     *
     * @param array<string, mixed>|null $pickupData
     *
     * @return array<string, mixed>|null
     */
    private function mapPickup(?array $pickupData): ?array
    {
        if (! $pickupData) {
            return null;
        }

        return self::camelKeys($pickupData);
    }

    /**
     * Convert each key to camelCase, in the nested arrays too. A camelCase key stays as it is.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function camelKeys(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[is_string($key) ? Str::camel($key) : $key] = is_array($value) ? self::camelKeys($value) : $value;
        }

        return $result;
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
