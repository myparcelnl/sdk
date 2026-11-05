<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class ShippingRulesPutShippingRulesDataShippingRulesItem
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
     * @var ShippingRulesPutShippingRulesDataShippingRulesItemCriteria
     */
    public ShippingRulesPutShippingRulesDataShippingRulesItemCriteria $criteria;
    /**
     * @var ShippingRulesPutShippingRulesDataShippingRulesItemImplications
     */
    public ShippingRulesPutShippingRulesDataShippingRulesItemImplications $implications;
}