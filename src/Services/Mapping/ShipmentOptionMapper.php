<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Mapping;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptions;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptionsReturnsOptions;
use ReflectionClass;
use ReflectionProperty;

/**
 * Maps option names to the properties of the capabilities v2 options model, such as `requiresSignature`,
 * and back to the v1 option names of the shipments endpoint.
 *
 *     $mapper = new ShipmentOptionMapper();
 *     $mapper->v2PropertyFromName('signature');           // 'requiresSignature'
 *     $mapper->v2PropertyFromName('tracked');             // null
 *     $mapper->v1NameFromV2Property('requiresSignature'); // 'signature'
 *
 * Options have no numeric IDs. `v2PropertyFromName()` only covers names accepted by
 * `CapabilitiesPostCapabilitiesRequestV2Options`: a name without a property, such as `tracked`, gives
 * null, so a caller can check option names before it fills the options of a capabilities request.
 *
 * @see \MyParcelNL\Sdk\Services\Mapping\ApiMapperService
 */
final class ShipmentOptionMapper
{
    /**
     * Legacy option names whose meaning has a different name in the v2 request model.
     *
     * @var array<string, string>
     */
    private const LEGACY_TO_V2_PROPERTIES = [
        'signature'                => 'requiresSignature',
        'only_recipient'           => 'recipientOnlyDelivery',
        'age_check'                => 'requiresAgeVerification',
        'receipt_code'             => 'requiresReceiptCode',
        'large_format'             => 'oversizedPackage',
        'printerless_return'       => 'printReturnLabelAtDropOff',
        'collect'                  => 'scheduledCollection',
        'return'                   => 'returnOnFirstFailedDelivery',
        'cash_on_delivery'         => 'requiresCashOnDelivery',
        'drop_off_at_postal_point' => 'deliverAtPostalPoint',
        'extra_assurance'          => 'additionalInsurance',
    ];

    /**
     * The concrete v2 request has no tracked property. Its noTracking property has the opposite
     * meaning, and the option values are configuration objects that cannot be inverted as booleans.
     *
     * @var string[]
     */
    private const UNSUPPORTED_OPTIONS = ['tracked'];

    /**
     * @var null|list<string>
     */
    private static ?array $v2Properties = null;

    /**
     * @var null|array<string, string>
     */
    private static ?array $v1Names = null;

    /**
     * Return the v2 property for a legacy option name, a v2 JSON name or a snake_case name.
     * For example, signature, requiresSignature and requires_signature all resolve to requiresSignature.
     * Unknown and unsupported names return null.
     *
     * @param  string $name
     *
     * @return null|string
     */
    public function v2PropertyFromName(string $name): ?string
    {
        if (in_array($name, self::UNSUPPORTED_OPTIONS, true)) {
            return null;
        }

        $properties = self::v2Properties();
        $property   = self::LEGACY_TO_V2_PROPERTIES[$name] ?? $name;

        if (in_array($property, $properties, true)) {
            return $property;
        }

        // A snake_case name, a case variant or a name with spaces also resolves.
        $normalised = strtolower(str_replace(['_', ' '], '', $property));

        foreach ($properties as $candidate) {
            if (strtolower($candidate) === $normalised) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Get the v1 option name of the shipments endpoint for an option of a capabilities v2 response, such as
     * `signature` for `requiresSignature` or `requires_signature`. Returns null when the endpoint has none.
     *
     * @param  string $property The v2 property of the option.
     *
     * @return null|string
     */
    public function v1NameFromV2Property(string $property): ?string
    {
        return self::v1Names()[$this->v2PropertyFromName($property) ?? $property] ?? null;
    }

    /**
     * Return the v1 option names of the shipment options and the returns options, by v2 property and by
     * PHP property of the shipment options.
     *
     * @return array<string, string>
     */
    private static function v1Names(): array
    {
        if (null === self::$v1Names) {
            $v1Names = (new RefShipmentShipmentOptions())->definedProperties()
                + (new RefShipmentShipmentOptionsReturnsOptions())->definedProperties();

            self::$v1Names = array_intersect(array_flip(self::LEGACY_TO_V2_PROPERTIES), $v1Names) + $v1Names;
        }

        return self::$v1Names;
    }

    /**
     * Return the properties of the v2 options model.
     *
     * @return list<string>
     */
    private static function v2Properties(): array
    {
        if (null === self::$v2Properties) {
            // Jane generates `definedProperties()` only for a model whose schema allows additional
            // properties. This schema does not, so the names come from reflection.
            self::$v2Properties = array_map(
                static fn (ReflectionProperty $property): string => $property->getName(),
                (new ReflectionClass(CapabilitiesPostCapabilitiesRequestV2Options::class))->getProperties(ReflectionProperty::IS_PUBLIC)
            );
        }

        return self::$v2Properties;
    }

    /**
     * @internal Used to check exceptions against generated definitions.
     *
     * @return array<string, string>
     */
    public static function aliases(): array
    {
        return self::LEGACY_TO_V2_PROPERTIES;
    }

    /**
     * @internal Used to check unsupported options against generated definitions.
     *
     * @return string[]
     */
    public static function unmappableOptions(): array
    {
        return self::UNSUPPORTED_OPTIONS;
    }
}
