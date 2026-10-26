<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorBadRequest implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $message;
    /**
     * Internal request ID, useful for debugging purposes.
     *
     * @var string
     */
    public string $requestId;
    /**
     * The server will not process the request due to a client error.
     *
     * @var CommonHttpStatusCode4xxClientError400BadRequest|int
     */
    public CommonHttpStatusCode4xxClientError400BadRequest|int $statusCode;
    /**
     * @var list<CommonResponsesUserErrorBadRequestErrorsItem>
     */
    public array $errors;
    public function definedProperties(): array
    {
        return ['message' => 'message', 'requestId' => 'request_id', 'statusCode' => 'status_code', 'errors' => 'errors'];
    }
}