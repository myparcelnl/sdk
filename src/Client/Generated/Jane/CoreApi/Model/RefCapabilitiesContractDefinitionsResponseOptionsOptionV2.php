<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 implements AdditionalPropertiesInterface
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
    public function definedProperties(): array
    {
        return ['isSelectedByDefault' => 'isSelectedByDefault', 'isRequired' => 'isRequired'];
    }
}