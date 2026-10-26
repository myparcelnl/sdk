<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentResponsesPickupLocationsDefinitionsLocation
{
    /**
     * @var string
     */
    public string $locationName;
    /**
     * @var string|null
     */
    public ?string $retailNetworkId;
    /**
     * @var string
     */
    public string $distance;
    /**
     * @var string
     */
    public string $latitude;
    /**
     * @var string
     */
    public string $longitude;
    /**
     * @var string
     */
    public string $locationCode;
    /**
     * @var string|null
     */
    public ?string $phoneNumber;
    /**
     * @var RefShipmentLocationType|string|null
     */
    public RefShipmentLocationType|string|null $type;
    /**
     * @var ShipmentResponsesPickupLocationsDefinitionsOpeningHours
     */
    public ShipmentResponsesPickupLocationsDefinitionsOpeningHours $openingHours;
}