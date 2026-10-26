<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class UnpreparePackagesForShipmentPostBodyItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the order.
     *
     * @var string
     */
    public string $id;
    /**
     * @var list<string>
     */
    public array $packageIds;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'packageIds' => 'packageIds'];
    }
}