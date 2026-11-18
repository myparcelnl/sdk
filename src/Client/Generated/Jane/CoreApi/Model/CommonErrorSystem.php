<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonErrorSystem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The server failed to fulfill an apparently valid request
     *
     * @var int
     */
    public int $status;
    /**
     * @var int
     */
    public int $code;
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