<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class ShippingRulesResponsesShippingRulesPaginatedData
{
    /**
     * @var list<RefShippingRulesShippingRule>
     */
    public array $shippingRules;
    /**
     * Total number of shipping rules matching the request across all pages.
     *
     * @var int
     */
    public int $results;
    /**
     * Request a specific page of the results, used for paginated results.
     *
     * @var int
     */
    public int $page;
    /**
     * Specify the number of resources returned per page, used for paginated results.
     *
     * @var int
     */
    public int $size;
}