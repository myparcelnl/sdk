<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class EditNotePostBodyItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the order.
     *
     * @var string
     */
    public string $id;
    /**
     * The unique identifier of a note of an order.
     *
     * @var string
     */
    public string $noteId;
    /**
     * The text of the note.
     *
     * @var string
     */
    public string $text;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'noteId' => 'noteId', 'text' => 'text'];
    }
}