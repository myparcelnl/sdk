<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentDefsShipmentPickup
{
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
}