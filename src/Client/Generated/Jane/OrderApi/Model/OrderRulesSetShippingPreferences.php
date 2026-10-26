<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderRulesSetShippingPreferences implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The path of the rule.
     *
     * @var list<mixed>
     */
    public array $path;
    /**
     * The date and time the rule was applied.
     *
     * @var mixed
     */
    public $appliedAt;
    /**
     * How the rule was applied.
     *
     * @var OrderRulesSetShippingPreferencesResult|string
     */
    public OrderRulesSetShippingPreferencesResult|string $result;
    public function definedProperties(): array
    {
        return ['path' => 'path', 'appliedAt' => 'appliedAt', 'result' => 'result'];
    }
}