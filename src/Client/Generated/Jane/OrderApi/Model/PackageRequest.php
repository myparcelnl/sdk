<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class PackageRequest implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var list<PackageRequestLinesItem>
     */
    public array $lines;
    /**
     * The physical dimensions.
     *
     * @var PhysicalPropertiesWeightRequired
     */
    public PhysicalPropertiesWeightRequired $physicalProperties;
    public function definedProperties(): array
    {
        return ['lines' => 'lines', 'physicalProperties' => 'physicalProperties'];
    }
}