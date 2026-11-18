<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ProblemDetailsInvalidRequestSyntaxErrorsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $detail;
    /**
     * This is a JSON pointer to the request (body, parameters, ...). When an aggregate has a conflict (e.g. not allowed due to the status) this value is for bulk request on the index (/body/{index}) and for any other on root (/).
     *
     * @var string
     */
    public string $pointer;
    public function definedProperties(): array
    {
        return ['detail' => 'detail', 'pointer' => 'pointer'];
    }
}