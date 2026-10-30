<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class LineExternalReference implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The order line ID as defined in the source system.
     *
     * @var string
     */
    public string $sourceId;
    public function definedProperties(): array
    {
        return ['sourceId' => 'sourceId'];
    }
}