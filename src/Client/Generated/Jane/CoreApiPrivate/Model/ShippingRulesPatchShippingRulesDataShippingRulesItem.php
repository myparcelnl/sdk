<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class ShippingRulesPatchShippingRulesDataShippingRulesItem
{
    /**
     * @var int
     */
    public int $id;
    /**
     * @var int
     */
    public int $accountId;
    /**
     * @var int
     */
    public int $shopId;
    /**
     * @var float
     */
    public float $active;
    /**
     * @var ShippingRulesPatchShippingRulesDataShippingRulesItemCriteria
     */
    public ShippingRulesPatchShippingRulesDataShippingRulesItemCriteria $criteria;
    /**
     * @var ShippingRulesPatchShippingRulesDataShippingRulesItemImplications
     */
    public ShippingRulesPatchShippingRulesDataShippingRulesItemImplications $implications;
}