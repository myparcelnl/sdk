<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefShipmentPickup
{
    /**
     * @var string
     */
    public string $postalCode;
    /**
     * @var string
     */
    public string $locationName;
    /**
     * @var string
     */
    public string $city;
    /**
     * @var string
     */
    public string $street;
    /**
     * @var string
     */
    public string $number;
    /**
     * @var string|null
     */
    public ?string $numberSuffix;
    /**
     * @var string
     */
    public string $boxNumber;
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
     * @var string
     */
    public string $locationCode;
    /**
     * @var RefShipmentBpostBpostPugoType|RefShipmentLocationType|int|string
     */
    public $locationType;
    /**
     * @var string
     */
    public string $retailNetworkId;
}