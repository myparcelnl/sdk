<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentDefsDeliveryOptionsPickup
{
    /**
     * @var \DateTimeInterface|null
     */
    public ?\DateTimeInterface $date;
    /**
     * @var list<ShipmentDefsDeliveryOptionsPickupTime>
     */
    public array $time;
    /**
     * @var string
     */
    public string $location;
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
    public string $postalCode;
    /**
     * @var string
     */
    public string $city;
    /**
     * @var string
     */
    public string $cc;
    /**
     * @var string|null
     */
    public ?string $startTime;
    /**
     * @var int
     */
    public int $price;
    /**
     * @var ShipmentDefsDeliveryOptionsPickupPriceComment|string
     */
    public ShipmentDefsDeliveryOptionsPickupPriceComment|string $priceComment;
    /**
     * @var string
     */
    public string $comment;
    /**
     * @var string
     */
    public string $phoneNumber;
    /**
     * @var ShipmentDefsDeliveryOptionsOpeningHours
     */
    public ShipmentDefsDeliveryOptionsOpeningHours $openingHours;
    /**
     * @var string
     */
    public string $distance;
    /**
     * @var float|string
     */
    public $latitude;
    /**
     * @var float|string
     */
    public $longitude;
    /**
     * @var string
     */
    public string $locationCode;
    /**
     * @var string|null
     */
    public ?string $retailNetworkId;
    /**
     * @var mixed
     */
    public $holiday;
}