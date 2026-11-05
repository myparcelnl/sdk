<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class NoteFromImported implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var NoteFromImportedType|string
     */
    public NoteFromImportedType|string $type;
    /**
     * The unique identifier of a note of an order.
     *
     * @var string
     */
    public string $id;
    /**
     * The date and time the note was created.
     *
     * @var mixed
     */
    public $notedAt;
    /**
     * The origin of the note.
     *
     * @var NoteFromImportedOrigin|string
     */
    public NoteFromImportedOrigin|string $origin;
    /**
     * The text of the note.
     *
     * @var string
     */
    public string $text;
    /**
     * The author of the note.
     *
     * @var string
     */
    public string $author;
    /**
     * The locale of the note.
     *
     * @var mixed
     */
    public $locale;
    /**
     * The date and time the note was last updated.
     *
     * @var mixed
     */
    public $updatedAt;
    public function definedProperties(): array
    {
        return ['type' => 'type', 'id' => 'id', 'notedAt' => 'notedAt', 'origin' => 'origin', 'text' => 'text', 'author' => 'author', 'locale' => 'locale', 'updatedAt' => 'updatedAt'];
    }
}