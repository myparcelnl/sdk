<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ProductToCreateDescriptionsItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The text of one of the product descriptions.
     *
     * @var string
     */
    public string $text;
    /**
     * The language code (ISO 639-1) of one of the product descriptions.
     *
     * @var string
     */
    public string $languageCode;
    public function definedProperties(): array
    {
        return ['text' => 'text', 'languageCode' => 'languageCode'];
    }
}