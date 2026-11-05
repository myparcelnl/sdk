<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorNotFoundErrorsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Did not find the requested target resource.
     *
     * @var CommonHttpStatusCode4xxClientError404NotFound|int
     */
    public CommonHttpStatusCode4xxClientError404NotFound|int $status;
    /**
     * @var int
     */
    public int $code;
    public function definedProperties(): array
    {
        return ['status' => 'status', 'code' => 'code'];
    }
}