<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class RefShippingRulesShippingRule
{
    /**
     * Unique identifier of the shipping rule. Null on rules that have not yet been persisted.
     *
     * @var null|int
     */
    public ?int $id;
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
     * Criteria that determine when a shipping rule applies. Matched against the destination of an incoming shipment.
     *
     * @var RefShippingRulesCriteria
     */
    public RefShippingRulesCriteria $criteria;
    /**
     * Implications applied to a shipment that matches a shipping rule, extended with a reference to the originating rule.
     *
     * @var RefShippingRulesImplications
     */
    public RefShippingRulesImplications $implications;
    /**
     * Creation timestamp in 'YYYY-MM-DD HH:MM:SS' format. Null when no creation time is available.
     *
     * @var null|string
     */
    public ?string $created;
}