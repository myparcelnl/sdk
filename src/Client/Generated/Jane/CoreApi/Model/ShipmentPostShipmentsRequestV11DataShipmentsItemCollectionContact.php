<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $cc;
    /**
     * @var string
     */
    public string $city;
    /**
     * @var string
     */
    public string $company;
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $firstName;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactGender|string
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactGender|string $gender;
    /**
     * @var string
     */
    public string $lastName;
    /**
     * @var string|int
     */
    public $number;
    /**
     * @var string|null
     */
    public ?string $numberSuffix;
    /**
     * @var string
     */
    public string $phone;
    /**
     * @var string
     */
    public string $postalCode;
    /**
     * @var string
     */
    public string $street;
    /**
     * @var string|null
     */
    public ?string $streetAdditionalInfo;
    public function definedProperties(): array
    {
        return ['cc' => 'cc', 'city' => 'city', 'company' => 'company', 'email' => 'email', 'firstName' => 'first_name', 'gender' => 'gender', 'lastName' => 'last_name', 'number' => 'number', 'numberSuffix' => 'number_suffix', 'phone' => 'phone', 'postalCode' => 'postal_code', 'street' => 'street', 'streetAdditionalInfo' => 'street_additional_info'];
    }
}