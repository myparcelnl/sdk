<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ContactMultiEmailBusiness implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * A value that is either a full locale or just a language code.
     *
     * @var mixed
     */
    public $locale;
    /**
     * The phone number of the contact.
     *
     * @var string
     */
    public string $phoneNumber;
    /**
     * Discriminator for business contacts.
     *
     * @var ContactMultiEmailBusinessType|string
     */
    public ContactMultiEmailBusinessType|string $type;
    /**
     * The name of the company.
     *
     * @var string
     */
    public string $company;
    /**
     * The name of the contact person.
     *
     * @var string
     */
    public string $attention;
    /**
     * A collection of VAT registration numbers of the company, indexed by ISO country code.
     *
     * @var iterable<string, string>
     */
    public iterable $vatNumbersByCountry;
    /**
     * The VOEC number of the company.
     *
     * @var string
     */
    public string $voecNumber;
    /**
     * The EORI number of the company.
     *
     * @var string
     */
    public string $eoriNumber;
    /**
     * The email addresses of the contact. The first email address will be used as the primary email address. The remaining will be used as extra email addresses if the right permissions are present.
     *
     * @var list<string>
     */
    public array $emailAddresses;
    public function definedProperties(): array
    {
        return ['locale' => 'locale', 'phoneNumber' => 'phoneNumber', 'type' => 'type', 'company' => 'company', 'attention' => 'attention', 'vatNumbersByCountry' => 'vatNumbersByCountry', 'voecNumber' => 'voecNumber', 'eoriNumber' => 'eoriNumber', 'emailAddresses' => 'emailAddresses'];
    }
}