<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorNotAcceptableErrorsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The target resource doesn't have an acceptable representation for the user agent.
     *
     * @var CommonHttpStatusCode4xxClientError406NotAcceptable|int
     */
    public CommonHttpStatusCode4xxClientError406NotAcceptable|int $status;
    /**
     * @var int
     */
    public int $code;
    public function definedProperties(): array
    {
        return ['status' => 'status', 'code' => 'code'];
    }
}