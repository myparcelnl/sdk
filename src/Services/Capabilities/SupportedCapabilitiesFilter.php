<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;

/**
 * Removes the values of a capabilities response that this SDK version cannot send to the shipments endpoint,
 * such as a new carrier. A shipment with such a value fails when the request goes out.
 *
 *     $capabilities = SupportedCapabilitiesFilter::filter($response->results);
 */
final class SupportedCapabilitiesFilter
{
    /**
     * Remove the unsupported carriers, delivery types, package types and options from capabilities or
     * contract definitions. Returns copies, so the models you pass stay unchanged.
     *
     * @template T of RefCapabilitiesResponseCapabilityV2|RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2
     *
     * @param  T[] $models
     *
     * @return list<T>
     */
    public static function filter(array $models): array
    {
        $supported = [];

        foreach ($models as $model) {
            if (! self::isCarrierSupported($model->carrier)) {
                continue;
            }

            $copy = CoreApiModels::copy($model);

            if (isset($copy->deliveryTypes)) {
                $copy->deliveryTypes = self::keep($copy->deliveryTypes, [self::class, 'isDeliveryTypeSupported']);
            }

            if (isset($copy->packageTypes)) {
                $copy->packageTypes = self::keep($copy->packageTypes, [self::class, 'isPackageTypeSupported']);
            }

            if (isset($copy->options)) {
                self::removeUnsupportedOptions($copy->options);
            }

            $supported[] = $copy;
        }

        return $supported;
    }

    /**
     * Check if a carrier of the capabilities API, such as `POSTNL`, has a v1 ID for the shipments endpoint.
     */
    public static function isCarrierSupported(RefCapabilitiesSharedCarrierV2|string $carrier): bool
    {
        return null !== ApiMapperService::forCarrier()->idFromV2Name(self::value($carrier));
    }

    /**
     * Check if a delivery type of the capabilities API, such as `MORNING_DELIVERY`, has a v1 ID for the
     * shipments endpoint and a delivery options name.
     */
    public static function isDeliveryTypeSupported(RefTypesDeliveryTypeV2|string $deliveryType): bool
    {
        $mapper = ApiMapperService::forDeliveryType();
        $name   = self::value($deliveryType);

        return null !== $mapper->legacyNameFromV2Name($name) && null !== $mapper->idFromV2Name($name);
    }

    /**
     * Check if a package type of the capabilities API, such as `MAILBOX`, has a v1 ID for the shipments
     * endpoint and a delivery options name.
     */
    public static function isPackageTypeSupported(RefShipmentPackageTypeV2|string $packageType): bool
    {
        $mapper = ApiMapperService::forPackageType();
        $name   = self::value($packageType);

        return null !== $mapper->legacyNameFromV2Name($name) && null !== $mapper->idFromV2Name($name);
    }

    /**
     * Check if an option of the capabilities API, such as `requiresSignature`, has a v1 option name for the
     * shipments endpoint.
     */
    public static function isOptionSupported(string $option): bool
    {
        return null !== (new ShipmentOptionMapper())->v1NameFromV2Property($option);
    }

    /**
     * Get the API value of an enum case, or the string as it is.
     */
    private static function value(\BackedEnum|string $value): string
    {
        return $value instanceof \BackedEnum ? (string) $value->value : $value;
    }

    /**
     * Keep the values that pass the check, as a list.
     *
     * @param  list<\BackedEnum|string> $values
     *
     * @return list<\BackedEnum|string>
     */
    private static function keep(array $values, callable $isSupported): array
    {
        return array_values(array_filter($values, $isSupported));
    }

    /**
     * Remove each unsupported option. A defined option is unset, because its typed property rejects null.
     * An option that the model does not define is in its additional properties, and is removed there.
     */
    private static function removeUnsupportedOptions(
        RefCapabilitiesResponseOptionsOptionsV2|RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2 $options
    ): void {
        foreach (array_keys($options->definedProperties()) as $option) {
            if (isset($options->{$option}) && ! self::isOptionSupported($option)) {
                unset($options->{$option});
            }
        }

        foreach (array_keys(iterator_to_array($options->additionalPropertyEntries())) as $option) {
            if (! self::isOptionSupported($option)) {
                $options->offsetUnset($option);
            }
        }
    }
}
