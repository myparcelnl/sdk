<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ConnectTokenPostJsonResponse500 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ConnectTokenPostJsonResponse500Error|string
     */
    public ConnectTokenPostJsonResponse500Error|string $error;
    public function definedProperties(): array
    {
        return ['error' => 'error'];
    }
}