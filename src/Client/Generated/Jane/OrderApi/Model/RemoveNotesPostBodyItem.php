<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class RemoveNotesPostBodyItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the order.
     *
     * @var string
     */
    public string $id;
    /**
     * Note IDs to remove from the order.
     *
     * @var list<string>
     */
    public array $noteIds;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'noteIds' => 'noteIds'];
    }
}