<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class RefShippingRulesCriteria
{
    /**
     * ISO 3166-1 alpha-2 country code. Null when the rule applies to shipments regardless of destination country.
     *
     * @var null|string
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