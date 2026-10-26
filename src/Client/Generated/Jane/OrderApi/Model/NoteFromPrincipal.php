<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class NoteFromPrincipal implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var NoteFromPrincipalType|string
     */
    public NoteFromPrincipalType|string $type;
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
     * The principal who crated the note.
     *
     * @var string
     */
    public string $principal;
    /**
     * The text of the note.
     *
     * @var string
     */
    public string $text;
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
        return ['type' => 'type', 'id' => 'id', 'notedAt' => 'notedAt', 'principal' => 'principal', 'text' => 'text', 'locale' => 'locale', 'updatedAt' => 'updatedAt'];
    }
}