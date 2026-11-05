<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderToCreateShippingRecipient implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The address of the recipient.
     *
     * @var OrderToCreateShippingRecipientAddress
     */
    public OrderToCreateShippingRecipientAddress $address;
    /**
     * The contact details of the recipient.
     *
     * @var ContactMultiEmailBusiness|ContactMultiEmailPrivate
     */
    public $contact;
    public function definedProperties(): array
    {
        return ['address' => 'address', 'contact' => 'contact'];
    }
}