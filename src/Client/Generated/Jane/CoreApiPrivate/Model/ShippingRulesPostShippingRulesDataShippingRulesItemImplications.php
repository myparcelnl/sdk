<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class ShippingRulesPostShippingRulesDataShippingRulesItemImplications
{
    /**
     * This enumeration represents the Carriers. The value is the Carrier id.
     *
     * @var RefTypesCarrier|int
     */
    public RefTypesCarrier|int $carrierId;
    /**
     * @var int|null
     */
    public ?int $contractId;
    /**
     * @var RefShipmentShipmentOptions|null
     */
    public ?RefShipmentShipmentOptions $shipmentOptions;
    /**
     * @var ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsPhysicalProperties
     */
    public ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsPhysicalProperties $physicalProperties;
}