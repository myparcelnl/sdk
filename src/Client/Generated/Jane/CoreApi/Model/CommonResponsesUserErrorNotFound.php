<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorNotFound implements AdditionalPropertiesInterface
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
     * Did not find the requested target resource.
     *
     * @var CommonHttpStatusCode4xxClientError404NotFound|int
     */
    public CommonHttpStatusCode4xxClientError404NotFound|int $statusCode;
    /**
     * @var list<CommonResponsesUserErrorNotFoundErrorsItem>
     */
    public array $errors;
    public function definedProperties(): array
    {
        return ['message' => 'message', 'requestId' => 'request_id', 'statusCode' => 'status_code', 'errors' => 'errors'];
    }
}