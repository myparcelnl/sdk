<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorPermissionDenied implements AdditionalPropertiesInterface
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
     * The request lacks valid authentication credentials for the target resource.
     *
     * @var CommonHttpStatusCode4xxClientError401Unauthorized|int
     */
    public CommonHttpStatusCode4xxClientError401Unauthorized|int $statusCode;
    /**
     * @var list<CommonResponsesUserErrorPermissionDeniedErrorsItem>
     */
    public array $errors;
    public function definedProperties(): array
    {
        return ['message' => 'message', 'requestId' => 'request_id', 'statusCode' => 'status_code', 'errors' => 'errors'];
    }
}