<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class OrderBillingDetails implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * A physical address.
     *
     * @var Address
     */
    public Address $address;
    /**
     * A contact.
     *
     * @var mixed
     */
    public $contact;
    public function definedProperties(): array
    {
        return ['address' => 'address', 'contact' => 'contact'];
    }
}