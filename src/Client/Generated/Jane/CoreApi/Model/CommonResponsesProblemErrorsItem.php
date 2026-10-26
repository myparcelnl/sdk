<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonResponsesProblemErrorsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $type;
    /**
     * @var string
     */
    public string $title;
    /**
     * @var string
     */
    public string $detail;
    /**
     * @var string
     */
    public string $instance;
    public function definedProperties(): array
    {
        return ['type' => 'type', 'title' => 'title', 'detail' => 'detail', 'instance' => 'instance'];
    }
}