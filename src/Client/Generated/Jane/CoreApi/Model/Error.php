<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class Error implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var int
     */
    public $status;
    /**
     * @var CommonErrorUserCodeAuth|int
     */
    public $code;
    /**
     * @var string
     */
    public string $title;
    /**
     * @var string
     */
    public string $message;
    public function definedProperties(): array
    {
        return ['status' => 'status', 'code' => 'code', 'title' => 'title', 'message' => 'message'];
    }
}