<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentDefsShipmentSender
{
    /**
     * @var int
     */
    public int $accountId;
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
    public string $cc;
    /**
     * @var null|string
     */
    public ?string $region;
    /**
     * @var string
     */
    public string $area;
    /**
     * @var null|string
     */
    public ?string $phone;
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
}