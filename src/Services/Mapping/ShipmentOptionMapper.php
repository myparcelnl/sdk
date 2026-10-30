<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Mapping;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options;
use MyParcelNL\Sdk\Support\Str;
use ReflectionClass;
use ReflectionProperty;

/**
 * Maps shipment option names to properties of the capabilities v2 request model.
 *
 *     $mapper = new ShipmentOptionMapper();
 *     $mapper->v2PropertyFromName('signature'); // 'requires_signature'
 *     $mapper->v2PropertyFromName('tracked');   // null
 *
 * Options have no numeric IDs. This mapper only covers names accepted by
 * CapabilitiesPostCapabilitiesRequestV2Options.
 * Use CapabilitiesRequest::getUnsupportedOptions() to check a request before sending it.
 *
 * @see \MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest::getUnsupportedOptions()
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
        'signature'                => 'requires_signature',
        'only_recipient'           => 'recipient_only_delivery',
        'age_check'                => 'requires_age_verification',
        'receipt_code'             => 'requires_receipt_code',
        'large_format'             => 'oversized_package',
        'printerless_return'       => 'print_return_label_at_drop_off',
        'collect'                  => 'scheduled_collection',
        'return'                   => 'return_on_first_failed_delivery',
        'cash_on_delivery'         => 'requires_cash_on_delivery',
        'drop_off_at_postal_point' => 'deliver_at_postal_point',
        'extra_assurance'          => 'additional_insurance',
    ];

    /**
     * The concrete v2 request has no tracked property. Its no_tracking property has the opposite
     * meaning, and the option values are configuration objects that cannot be inverted as booleans.
     *
     * @var string[]
     */
    private const UNSUPPORTED_OPTIONS = ['tracked'];

    /**
     * @var null|array<string, string>
     */
    private static ?array $v2Properties = null;

    /**
     * Return the snake_case v2 name for a legacy option name or a v2 JSON/property name.
     * For example, both signature and requiresSignature resolve to requires_signature.
     * Unknown and unsupported names return null.
     *
     * The snake_case name is the property name of the openapi-generator models of v11. The PDK reads
     * it, so it stays the same with Jane, which names the property requiresSignature.
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
        $property   = self::LEGACY_TO_V2_PROPERTIES[$name] ?? $properties[$name] ?? $name;

        if (in_array($property, $properties, true)) {
            return $property;
        }

        // Existing calls also accept case variants and spaces, as PHP's method lookup did before.
        $normalised = strtolower(str_replace(['_', ' '], '', $property));

        foreach ($properties as $candidate) {
            if (strtolower(str_replace('_', '', $candidate)) === $normalised) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Return the options of the v2 capabilities request as v2 JSON name => snake_case name.
     *
     * The snake_case name is the PHP property that openapi-generator generated, such as
     * requires_signature. This mapper keeps returning it, because the PDK reads it. Jane names the
     * PHP property after the v2 JSON name, such as requiresSignature.
     *
     * The method reads the properties with reflection, not with definedProperties(). Jane generates
     * definedProperties() for only 81 of the 415 Core API models, and not for this one.
     *
     * @return array<string, string>
     */
    private static function v2Properties(): array
    {
        if (null === self::$v2Properties) {
            self::$v2Properties = [];
            $reflection         = new ReflectionClass(CapabilitiesPostCapabilitiesRequestV2Options::class);

            foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                self::$v2Properties[$property->getName()] = Str::snake($property->getName());
            }
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
