<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ConnectTokenPostResponse401 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ConnectTokenPostResponse401Error|string
     */
    public ConnectTokenPostResponse401Error|string $error;
    public function definedProperties(): array
    {
        return ['error' => 'error'];
    }
}