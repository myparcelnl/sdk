<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderShippingRecipient implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * A physical address.
     *
     * @var Address
     */
    public Address $address;
    /**
     * A contact entity capable of holding multiple email addresses.
     *
     * @var mixed
     */
    public $contact;
    public function definedProperties(): array
    {
        return ['address' => 'address', 'contact' => 'contact'];
    }
}