<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentOptionsPackageTypeReturns implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefShipmentOptionsPackageTypeReturnsPackageType|int
     */
    public RefShipmentOptionsPackageTypeReturnsPackageType|int $packageType;
    public function definedProperties(): array
    {
        return ['packageType' => 'package_type'];
    }
}