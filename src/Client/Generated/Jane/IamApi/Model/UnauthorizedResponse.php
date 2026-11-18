<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\AdditionalPropertiesInterface;
class UnauthorizedResponse implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var UnauthorizedResponseMessage|string
     */
    public UnauthorizedResponseMessage|string $message;
    public function definedProperties(): array
    {
        return ['message' => 'message'];
    }
}