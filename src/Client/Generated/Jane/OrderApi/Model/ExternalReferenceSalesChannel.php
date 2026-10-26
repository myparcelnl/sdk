<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ExternalReferenceSalesChannel implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the sales channel.
     *
     * @var string
     */
    public string $id;
    /**
     * The name of the sales channel at the time of import.
     *
     * @var string
     */
    public string $name;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'name' => 'name'];
    }
}