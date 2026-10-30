<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorPermissionDeniedErrorsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The request lacks valid authentication credentials for the target resource.
     *
     * @var CommonHttpStatusCode4xxClientError401Unauthorized|int
     */
    public CommonHttpStatusCode4xxClientError401Unauthorized|int $status;
    /**
     * @var int
     */
    public int $code;
    public function definedProperties(): array
    {
        return ['status' => 'status', 'code' => 'code'];
    }
}