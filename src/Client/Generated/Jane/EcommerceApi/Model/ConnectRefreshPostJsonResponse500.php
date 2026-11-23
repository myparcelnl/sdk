<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ConnectRefreshPostJsonResponse500 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ConnectRefreshPostJsonResponse500Error|string
     */
    public ConnectRefreshPostJsonResponse500Error|string $error;
    public function definedProperties(): array
    {
        return ['error' => 'error'];
    }
}