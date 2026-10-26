<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorNotAcceptable implements AdditionalPropertiesInterface
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
     * The target resource doesn't have an acceptable representation for the user agent.
     *
     * @var CommonHttpStatusCode4xxClientError406NotAcceptable|int
     */
    public CommonHttpStatusCode4xxClientError406NotAcceptable|int $statusCode;
    /**
     * @var list<CommonResponsesUserErrorNotAcceptableErrorsItem>
     */
    public array $errors;
    public function definedProperties(): array
    {
        return ['message' => 'message', 'requestId' => 'request_id', 'statusCode' => 'status_code', 'errors' => 'errors'];
    }
}