<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class AddNotePostBodyItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the order.
     *
     * @var string
     */
    public string $id;
    /**
     * The text of the note.
     *
     * @var string
     */
    public string $text;
    /**
     * A value that is either a full locale or just a language code.
     *
     * @var mixed
     */
    public $locale;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'text' => 'text', 'locale' => 'locale'];
    }
}