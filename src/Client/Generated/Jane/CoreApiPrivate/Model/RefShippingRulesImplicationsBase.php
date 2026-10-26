<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class RefShippingRulesImplicationsBase
{
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
}