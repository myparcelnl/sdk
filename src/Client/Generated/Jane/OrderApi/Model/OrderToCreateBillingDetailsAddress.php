<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderToCreateBillingDetailsAddress implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The city of the address.
     *
     * @var string
     */
    public string $city;
    /**
     * ISO 3166-1 alpha-2 country code.
     *
     * @var string
     */
    public string $countryCode;
    /**
     * The street of the address.
     *
     * @var string
     */
    public string $street;
    /**
     * Additional information about the address.
     *
     * @var string
     */
    public string $additionalInformation;
    /**
     * The box number of the address, only applicable for specific country codes.
     *
     * @var string
     */
    public string $boxNumber;
    /**
     * The house number of the address.
     *
     * @var string
     */
    public string $houseNumber;
    /**
     * The house number suffix of the address.
     *
     * @var string
     */
    public string $houseNumberSuffix;
    /**
     * The postal code of the address.
     *
     * @var string
     */
    public string $postalCode;
    /**
     * The region of the address.
     *
     * @var string
     */
    public string $region;
    /**
     * The state of the address.
     *
     * @var string
     */
    public string $state;
    public function definedProperties(): array
    {
        return ['city' => 'city', 'countryCode' => 'countryCode', 'street' => 'street', 'additionalInformation' => 'additionalInformation', 'boxNumber' => 'boxNumber', 'houseNumber' => 'houseNumber', 'houseNumberSuffix' => 'houseNumberSuffix', 'postalCode' => 'postalCode', 'region' => 'region', 'state' => 'state'];
    }
}