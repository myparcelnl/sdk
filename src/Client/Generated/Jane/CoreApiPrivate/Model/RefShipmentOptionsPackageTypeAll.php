<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\AdditionalPropertiesInterface;
class RefShipmentOptionsPackageTypeAll implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Package Type.
     *
     * @var RefShipmentPackageType|int
     */
    public RefShipmentPackageType|int $packageType;
    public function definedProperties(): array
    {
        return ['packageType' => 'package_type'];
    }
}