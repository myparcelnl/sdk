<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class DropOff implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The date at which to drop off the package.
     *
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $date;
    public function definedProperties(): array
    {
        return ['date' => 'date'];
    }
}