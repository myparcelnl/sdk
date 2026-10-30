<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentDefsShipmentRecipient implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $person;
    /**
     * @var string
     */
    public string $firstName;
    /**
     * @var string
     */
    public string $lastName;
    /**
     * @var null|string
     */
    public ?string $company;
    /**
     * @var string
     */
    public string $street;
    /**
     * @var null|string
     */
    public ?string $streetAdditionalInfo;
    /**
     * @var null|string
     */
    public ?string $number;
    /**
     * @var null|string
     */
    public ?string $numberSuffix;
    /**
     * @var string
     */
    public string $postalCode;
    /**
     * @var string
     */
    public string $city;
    /**
     * @var string
     */
    public string $state;
    /**
     * @var string
     */
    public string $area;
    /**
     * @var null|string
     */
    public ?string $phone;
    /**
     * @var int
     */
    public int $accountId;
    /**
     * @var string
     */
    public string $cc;
    /**
     * @var null|string
     */
    public ?string $region;
    /**
     * @var null|string
     */
    public ?string $email;
    /**
     * @var null|string
     */
    public ?string $billingEmail;
    /**
     * @var list<null>|list<string>|null
     */
    public ?array $secondaryEmails;
    /**
     * @var null|string
     */
    public ?string $boxNumber;
    /**
     * @var string
     */
    public string $eoriNumber;
    /**
     * @var string
     */
    public string $vatNumber;
    /**
     * @var bool
     */
    public bool $invalid;
    public function definedProperties(): array
    {
        return ['person' => 'person', 'firstName' => 'first_name', 'lastName' => 'last_name', 'company' => 'company', 'street' => 'street', 'streetAdditionalInfo' => 'street_additional_info', 'number' => 'number', 'numberSuffix' => 'number_suffix', 'postalCode' => 'postal_code', 'city' => 'city', 'state' => 'state', 'area' => 'area', 'phone' => 'phone', 'accountId' => 'account_id', 'cc' => 'cc', 'region' => 'region', 'email' => 'email', 'billingEmail' => 'billing_email', 'secondaryEmails' => 'secondary_emails', 'boxNumber' => 'box_number', 'eoriNumber' => 'eori_number', 'vatNumber' => 'vat_number', 'invalid' => 'invalid'];
    }
}