<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentDefsShipmentPartnerTracktracesItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $uri;
    /**
     * @var string
     */
    public string $barcode;
    public function definedProperties(): array
    {
        return ['uri' => 'uri', 'barcode' => 'barcode'];
    }
}