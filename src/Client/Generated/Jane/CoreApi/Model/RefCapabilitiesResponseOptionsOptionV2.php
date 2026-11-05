<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesResponseOptionsOptionV2 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var bool
     */
    public bool $isSelectedByDefault;
    /**
     * @var bool
     */
    public bool $isRequired;
    /**
     * camelCase shipment option keys that are required
     *
     * @var list<string>
     */
    public array $requires;
    /**
     * camelCase shipment option keys that are excluded
     *
     * @var list<string>
     */
    public array $excludes;
    public function definedProperties(): array
    {
        return ['isSelectedByDefault' => 'isSelectedByDefault', 'isRequired' => 'isRequired', 'requires' => 'requires', 'excludes' => 'excludes'];
    }
}