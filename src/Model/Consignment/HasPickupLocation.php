<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

trait HasPickupLocation
{
    /**
     * @internal
     * @var string
     */
    public $pickup_cc;

    /**
     * @internal
     * @var string
     */
    public $pickup_city;

    /**
     * @internal
     * @var string
     */
    public $pickup_location_code = '';

    /**
     * @internal
     * @var string
     */
    public $pickup_location_name;

    /**
     * @internal
     * @var string
     */
    public $pickup_number;

    /**
     * @internal
     * @var string
     */
    public $pickup_postal_code;

    /**
     * @internal
     * @var string
     */
    public $pickup_street;

    /**
     * @internal
     * @var null|string
     */
    public $retail_network_id;

    /**
     * @return string|null
     */
    public function getPickupCity(): ?string
    {
        return $this->pickup_city;
    }

    /**
     * @return string|null
     */
    public function getPickupCountry(): ?string
    {
        return $this->pickup_cc;
    }

    /**
     * @return string
     */
    public function getPickupLocationCode(): string
    {
        return $this->pickup_location_code;
    }

    /**
     * @return string|null
     */
    public function getPickupLocationName(): ?string
    {
        return $this->pickup_location_name;
    }

    /**
     * @return null|string
     * @deprecated Use getRetailNetworkId instead
     */
    public function getPickupNetworkId(): ?string
    {
        return $this->getRetailNetworkId();
    }

    /**
     * @return string|null
     */
    public function getPickupNumber(): ?string
    {
        return $this->pickup_number;
    }

    /**
     * @return string|null
     */
    public function getPickupPostalCode(): ?string
    {
        return $this->pickup_postal_code;
    }

    /**
     * @return string|null
     */
    public function getPickupStreet(): ?string
    {
        return $this->pickup_street;
    }

    /**
     * @return null|string
     */
    public function getRetailNetworkId(): ?string
    {
        return $this->retail_network_id;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Hoofddorp
     * Required: Yes for pickup location.
     *
     * @param  string $pickupCity
     *
     * @return self
     */
    public function setPickupCity(string $pickupCity): self
    {
        $this->pickup_city = $pickupCity;

        return $this;
    }

    /**
     * @param  null|string $pickupCountry
     *
     * @return AbstractConsignment
     */
    public function setPickupCountry(?string $pickupCountry): self
    {
        $this->pickup_cc = $pickupCountry;

        return $this;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location.
     *
     * @param  string $pickupLocationCode
     *
     * @return self
     */
    public function setPickupLocationCode(string $pickupLocationCode): self
    {
        $this->pickup_location_code = $pickupLocationCode;

        return $this;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location.
     *
     * @param  string $pickup_location_name
     *
     * @return self
     */
    public function setPickupLocationName(string $pickup_location_name): self
    {
        $this->pickup_location_name = $pickup_location_name;

        return $this;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param  mixed $retailNetworkId
     *
     * @return self
     * @deprecated Use setRetailNetworkId instead
     */
    public function setPickupNetworkId($retailNetworkId): AbstractConsignment
    {
        return $this->setRetailNetworkId((string) $retailNetworkId);
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  270
     * Required: Yes for pickup location.
     *
     * @param  string $pickupNumber
     *
     * @return self
     */
    public function setPickupNumber(string $pickupNumber): self
    {
        $this->pickup_number = $pickupNumber;

        return $this;
    }

    /**
     * Pattern:  d{4}\s?[A-Z]{2}
     * Example:  2132BH
     * Required: Yes for pickup location.
     *
     * @param  string $pickupPostalCode
     *
     * @return self
     */
    public function setPickupPostalCode(string $pickupPostalCode): self
    {
        $this->pickup_postal_code = $pickupPostalCode;

        return $this;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Burgemeester van Stamplein
     * Required: Yes for pickup location.
     *
     * @param  string $pickupStreet
     *
     * @return self
     */
    public function setPickupStreet(string $pickupStreet): self
    {
        $this->pickup_street = $pickupStreet;

        return $this;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location.
     *
     * @param  string $retailNetworkId
     *
     * @return self
     */
    public function setRetailNetworkId(string $retailNetworkId): self
    {
        $this->retail_network_id = $retailNetworkId;

        return $this;
    }
}
