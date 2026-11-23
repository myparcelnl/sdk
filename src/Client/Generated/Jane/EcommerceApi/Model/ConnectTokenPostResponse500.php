<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ConnectTokenPostResponse500 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ConnectTokenPostResponse500Error|string
     */
    public ConnectTokenPostResponse500Error|string $error;
    public function definedProperties(): array
    {
        return ['error' => 'error'];
    }
}