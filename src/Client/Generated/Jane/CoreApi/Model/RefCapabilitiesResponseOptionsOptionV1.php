<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesResponseOptionsOptionV1 implements AdditionalPropertiesInterface
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
     * snake_case shipment option keys that are required
     *
     * @var list<string>
     */
    public array $requires;
    /**
     * snake_case shipment option keys that are excluded
     *
     * @var list<string>
     */
    public array $excludes;
    public function definedProperties(): array
    {
        return ['isSelectedByDefault' => 'is_selected_by_default', 'isRequired' => 'is_required', 'requires' => 'requires', 'excludes' => 'excludes'];
    }
}