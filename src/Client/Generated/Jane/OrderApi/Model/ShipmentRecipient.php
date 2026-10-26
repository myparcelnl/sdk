<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ShipmentRecipient implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * A physical address.
     *
     * @var Address
     */
    public Address $address;
    /**
     * @var ContactMultiEmailBusiness|ContactMultiEmailPrivate
     */
    public $contact;
    public function definedProperties(): array
    {
        return ['address' => 'address', 'contact' => 'contact'];
    }
}