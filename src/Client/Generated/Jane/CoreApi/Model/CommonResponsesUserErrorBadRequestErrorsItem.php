<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorBadRequestErrorsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The server will not process the request due to a client error.
     *
     * @var CommonHttpStatusCode4xxClientError400BadRequest|int
     */
    public CommonHttpStatusCode4xxClientError400BadRequest|int $status;
    /**
     * @var int
     */
    public int $code;
    public function definedProperties(): array
    {
        return ['status' => 'status', 'code' => 'code'];
    }
}