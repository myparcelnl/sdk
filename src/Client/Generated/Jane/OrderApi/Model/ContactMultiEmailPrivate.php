<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class ContactMultiEmailPrivate implements AdditionalPropertiesInterface
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
     * Discriminator for private contacts.
     *
     * @var ContactMultiEmailPrivateType|string
     */
    public ContactMultiEmailPrivateType|string $type;
    /**
     * The name of the contact person.
     *
     * @var string
     */
    public string $name;
    /**
     * The email addresses of the contact. The first email address will be used as the primary email address. The remaining will be used as extra email addresses if the right permissions are present.
     *
     * @var list<string>
     */
    public array $emailAddresses;
    public function definedProperties(): array
    {
        return ['locale' => 'locale', 'phoneNumber' => 'phoneNumber', 'type' => 'type', 'name' => 'name', 'emailAddresses' => 'emailAddresses'];
    }
}