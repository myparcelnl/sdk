<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class ShippingRulesPostShippingRulesDataShippingRulesItemCriteria
{
    /**
     * @var string|null
     */
    public ?string $country;
    /**
     * @var RefShippingRulesRegion|string|null
     */
    public RefShippingRulesRegion|string|null $region;
    /**
     * The type of shipping rule — applies either to outbound shipments or return shipments.
     *
     * @var RefShippingRulesType|string
     */
    public RefShippingRulesType|string $type;
}