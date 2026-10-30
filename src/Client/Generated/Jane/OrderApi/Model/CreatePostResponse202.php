<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class CreatePostResponse202 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The result per item, with the ID of each accepted order.
     *
     * @var list<mixed>
     */
    public array $results;
    public function definedProperties(): array
    {
        return ['results' => 'results'];
    }
}