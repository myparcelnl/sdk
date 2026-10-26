<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesUserErrorInvalidContentTypeErrorsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Content is in a format not supported by this method on the target resource.
     *
     * @var CommonHttpStatusCode4xxClientError415UnsupportedMediaType|int
     */
    public CommonHttpStatusCode4xxClientError415UnsupportedMediaType|int $status;
    /**
     * @var mixed
     */
    public $code;
    public function definedProperties(): array
    {
        return ['status' => 'status', 'code' => 'code'];
    }
}