<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalPropertiesInterface;
class RefShippingRulesImplications implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * This enumeration represents the Carriers. The value is the Carrier id.
     *
     * @var RefTypesCarrier|int
     */
    public RefTypesCarrier|int $carrierId;
    /**
     * Identifier of the carrier contract to use. Null when no specific contract is forced.
     *
     * @var null|int
     */
    public ?int $contractId;
    /**
     * Shipment options forced on a shipment that matches a shipping rule. Only keys with an explicit value appear; the object may be empty.
     *
     * @var RefShippingRulesShipmentOptions
     */
    public RefShippingRulesShipmentOptions $shipmentOptions;
    /**
     * Physical-property defaults applied to a shipment that matches a shipping rule. Each dimension is optional; the object may be empty.
     *
     * @var RefShippingRulesPhysicalProperties
     */
    public RefShippingRulesPhysicalProperties $physicalProperties;
    /**
     * Identifier of the shipping rule that produced these implications. Null when the implications come from a fallback resolution and no specific rule applied.
     *
     * @var null|int
     */
    public ?int $shippingRuleId;
    public function definedProperties(): array
    {
        return ['carrierId' => 'carrier_id', 'contractId' => 'contract_id', 'shipmentOptions' => 'shipment_options', 'physicalProperties' => 'physical_properties', 'shippingRuleId' => 'shipping_rule_id'];
    }
}