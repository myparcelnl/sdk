<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorInvalidContentType implements AdditionalPropertiesInterface
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
     * Content is in a format not supported by this method on the target resource.
     *
     * @var CommonHttpStatusCode4xxClientError415UnsupportedMediaType|int
     */
    public CommonHttpStatusCode4xxClientError415UnsupportedMediaType|int $statusCode;
    /**
     * @var list<CommonResponsesUserErrorInvalidContentTypeErrorsItem>
     */
    public array $errors;
    public function definedProperties(): array
    {
        return ['message' => 'message', 'requestId' => 'request_id', 'statusCode' => 'status_code', 'errors' => 'errors'];
    }
}