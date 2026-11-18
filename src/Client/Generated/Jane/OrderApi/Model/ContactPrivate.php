<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ContactPrivate implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * An email address.
     *
     * @var string
     */
    public string $emailAddress;
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
     * Discriminator for private contacts.
     *
     * @var ContactPrivateType|string
     */
    public ContactPrivateType|string $type;
    /**
     * The name of the contact person.
     *
     * @var string
     */
    public string $name;
    public function definedProperties(): array
    {
        return ['emailAddress' => 'emailAddress', 'locale' => 'locale', 'phoneNumber' => 'phoneNumber', 'type' => 'type', 'name' => 'name'];
    }
}