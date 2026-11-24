<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Capabilities;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesResponseCapabilityV2;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;

/**
 * Removes the values of a capabilities response that this SDK version cannot send to the shipments endpoint,
 * such as a new carrier. A shipment with such a value fails when the request goes out.
 *
 *     $capabilities = SupportedCapabilitiesFilter::filter($response->getResults());
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
            if (! self::isCarrierSupported($model->getCarrier())) {
                continue;
            }

            $copy = clone $model;
            $copy->setDeliveryTypes(self::keep($model->getDeliveryTypes(), [self::class, 'isDeliveryTypeSupported']));
            $copy->setPackageTypes(self::keep($model->getPackageTypes(), [self::class, 'isPackageTypeSupported']));

            $options = $model->getOptions();

            if (null !== $options) {
                $copy->setOptions(self::removeUnsupportedOptions(clone $options));
            }

            $supported[] = $copy;
        }

        return $supported;
    }

    /**
     * Check if a carrier of the capabilities API, such as `POSTNL`, has a v1 ID for the shipments endpoint.
     *
     * @param  string $carrier
     *
     * @return bool
     */
    public static function isCarrierSupported(string $carrier): bool
    {
        return null !== ApiMapperService::forCarrier()->idFromV2Name($carrier);
    }

    /**
     * Check if a delivery type of the capabilities API, such as `MORNING_DELIVERY`, has a v1 ID for the
     * shipments endpoint and a delivery options name.
     *
     * @param  string $deliveryType
     *
     * @return bool
     */
    public static function isDeliveryTypeSupported(string $deliveryType): bool
    {
        $mapper = ApiMapperService::forDeliveryType();

        return null !== $mapper->legacyNameFromV2Name($deliveryType) && null !== $mapper->idFromV2Name($deliveryType);
    }

    /**
     * Check if a package type of the capabilities API, such as `MAILBOX`, has a v1 ID for the shipments
     * endpoint and a delivery options name.
     *
     * @param  string $packageType
     *
     * @return bool
     */
    public static function isPackageTypeSupported(string $packageType): bool
    {
        $mapper = ApiMapperService::forPackageType();

        return null !== $mapper->legacyNameFromV2Name($packageType) && null !== $mapper->idFromV2Name($packageType);
    }

    /**
     * Check if an option of the capabilities API, such as `requires_signature` or `requiresSignature`,
     * has a v1 option name for the shipments endpoint.
     *
     * @param  string $option
     *
     * @return bool
     */
    public static function isOptionSupported(string $option): bool
    {
        return null !== (new ShipmentOptionMapper())->v1NameFromV2Property($option);
    }

    /**
     * Keep the values that pass the check, as a list.
     *
     * @param  null|string[] $values
     * @param  callable      $isSupported
     *
     * @return string[]
     */
    private static function keep(?array $values, callable $isSupported): array
    {
        return array_values(array_filter($values ?? [], $isSupported));
    }

    /**
     * Set each unsupported option to null. The setters reject null, and the serializer leaves the field out.
     *
     * @template T of \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ModelInterface&\ArrayAccess
     *
     * @param  T $options
     *
     * @return T
     */
    private static function removeUnsupportedOptions($options)
    {
        foreach (array_keys($options::openAPITypes()) as $option) {
            if (null !== $options->offsetGet($option) && ! self::isOptionSupported($option)) {
                $options->offsetSet($option, null);
            }
        }

        return $options;
    }
}
