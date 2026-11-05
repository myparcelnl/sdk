<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ExternalReferencesImport implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ExternalReferencesImportSource|string
     */
    public ExternalReferencesImportSource|string $source;
    /**
     * The order ID as defined in the source system.
     *
     * @var string
     */
    public string $sourceId;
    public function definedProperties(): array
    {
        return ['source' => 'source', 'sourceId' => 'sourceId'];
    }
}