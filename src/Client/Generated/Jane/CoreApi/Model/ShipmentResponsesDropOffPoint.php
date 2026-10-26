<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentResponsesDropOffPoint implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $locationCode;
    /**
     * @var string|null
     */
    public ?string $locationName;
    /**
     * @var string
     */
    public string $cc;
    /**
     * @var string|null
     */
    public ?string $state;
    /**
     * @var string
     */
    public string $city;
    /**
     * @var string
     */
    public string $postalCode;
    /**
     * @var string
     */
    public string $street;
    /**
     * @var string|int|null
     */
    public $number;
    /**
     * @var string|null
     */
    public ?string $numberSuffix;
    /**
     * @var string|null
     */
    public ?string $phone;
    /**
     * @var string|null
     */
    public ?string $reference;
    /**
     * @var float|string|null
     */
    public $longitude;
    /**
     * @var float|string|null
     */
    public $latitude;
    /**
     * @var list<CommonDefsDayOfWeek|int>|list<CommonDefsIsoDayOfWeek|int>|null
     */
    public ?array $availableDays;
    /**
     * @var string|null
     */
    public ?string $cutOffTime;
    /**
     * This enumeration represents the Carriers. The value is the Carrier id.
     *
     * @var RefTypesCarrier|int
     */
    public RefTypesCarrier|int $carrierId;
    /**
     * @var float|null
     */
    public ?float $distance;
    /**
     * @var ShipmentDefsOccupancy|null
     */
    public ?ShipmentDefsOccupancy $occupancy;
    /**
     * @var float|null
     */
    public ?float $occupancyToday;
    /**
     * @var bool|null
     */
    public ?bool $defaultDropOffPoint;
    /**
     * @var RefShipmentLocationType|string|null
     */
    public RefShipmentLocationType|string|null $type;
    /**
     * @var ShipmentResponsesDropOffPointDefinitionsOpeningHours|null
     */
    public ?ShipmentResponsesDropOffPointDefinitionsOpeningHours $openingHours;
    public function definedProperties(): array
    {
        return ['locationCode' => 'location_code', 'locationName' => 'location_name', 'cc' => 'cc', 'state' => 'state', 'city' => 'city', 'postalCode' => 'postal_code', 'street' => 'street', 'number' => 'number', 'numberSuffix' => 'number_suffix', 'phone' => 'phone', 'reference' => 'reference', 'longitude' => 'longitude', 'latitude' => 'latitude', 'availableDays' => 'available_days', 'cutOffTime' => 'cut_off_time', 'carrierId' => 'carrier_id', 'distance' => 'distance', 'occupancy' => 'occupancy', 'occupancyToday' => 'occupancy_today', 'defaultDropOffPoint' => 'default_drop_off_point', 'type' => 'type', 'openingHours' => 'opening_hours'];
    }
}