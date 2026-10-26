<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderToCreateBillingDetails implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var OrderToCreateBillingDetailsAddress
     */
    public OrderToCreateBillingDetailsAddress $address;
    /**
     * The billing contact of an order.
     *
     * @var ContactBusiness|ContactPrivate
     */
    public $contact;
    public function definedProperties(): array
    {
        return ['address' => 'address', 'contact' => 'contact'];
    }
}