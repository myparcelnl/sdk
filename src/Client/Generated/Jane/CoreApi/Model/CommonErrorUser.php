<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonErrorUser implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The request contains bad syntax or cannot be fulfilled
     *
     * @var int
     */
    public int $status;
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