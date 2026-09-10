<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Mapping;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesOptionsV2;

/**
 * Maps shipment option names to properties of the capabilities v2 request model.
 *
 *     $mapper = new ShipmentOptionMapper();
 *     $mapper->v2PropertyFromName('signature'); // 'requires_signature'
 *     $mapper->v2PropertyFromName('tracked');   // null
 *
 * Options have no numeric IDs. This mapper only covers names accepted by CapabilitiesOptionsV2.
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
     * Return the generated PHP property for a legacy option name or a v2 JSON/property name.
     * For example, both signature and requiresSignature resolve to requires_signature.
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

        // V2 JSON names use camelCase; generated PHP properties use snake_case.
        $v2NameToProperty = array_flip(CapabilitiesOptionsV2::attributeMap());
        $property        = self::LEGACY_TO_V2_PROPERTIES[$name] ?? $v2NameToProperty[$name] ?? $name;
        $setters         = CapabilitiesOptionsV2::setters();

        if (isset($setters[$property])) {
            return $property;
        }

        // Existing calls also accept case variants and spaces through PHP's method lookup.
        $setterName = 'set' . strtolower(str_replace(['_', ' '], '', $property));
        $property   = array_search($setterName, array_map('strtolower', $setters), true);

        return false === $property ? null : $property;
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
