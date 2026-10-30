<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentDefsShipmentDropOffPoint implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $locationCode;
    /**
     * @var string
     */
    public string $locationName;
    /**
     * @var string
     */
    public string $street;
    /**
     * @var string
     */
    public string $number;
    /**
     * @var null|string
     */
    public ?string $numberSuffix;
    /**
     * @var string
     */
    public string $boxNumber;
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
    public string $region;
    /**
     * @var string
     */
    public string $state;
    /**
     * @var string
     */
    public string $cc;
    /**
     * @var RefShipmentLocationType|RefShipmentBpostBpostPugoType|string|int
     */
    public $locationType;
    /**
     * @var string
     */
    public string $retailNetworkId;
    public function definedProperties(): array
    {
        return ['locationCode' => 'location_code', 'locationName' => 'location_name', 'street' => 'street', 'number' => 'number', 'numberSuffix' => 'number_suffix', 'boxNumber' => 'box_number', 'postalCode' => 'postal_code', 'city' => 'city', 'region' => 'region', 'state' => 'state', 'cc' => 'cc', 'locationType' => 'location_type', 'retailNetworkId' => 'retail_network_id'];
    }
}