<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

class DropOffPoint
{
    /**
     * @var null|string
     */
    private $box_number;

    /**
     * @var null|string
     */
    private $cc;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $location_code;

    /**
     * @var string
     */
    private $location_name;

    /**
     * @var string
     */
    private $number;

    /**
     * @var null|string
     */
    private $number_suffix;

    /**
     * @var string
     */
    private $postal_code;

    /**
     * @var null|string
     */
    private $region;

    /**
     * @var null|string
     */
    private $retail_network_id;

    /**
     * @var null|string
     */
    private $state;

    /**
     * @var string
     */
    private $street;

    public function __construct(array $receivedDropOffPoint = [])
    {
        if (empty($receivedDropOffPoint)) {
            return;
        }

        $this
            ->setCc($receivedDropOffPoint['cc'] ?? null)
            ->setCity($receivedDropOffPoint['city'])
            ->setLocationCode($receivedDropOffPoint['location_code'])
            ->setLocationName($receivedDropOffPoint['location_name'])
            ->setNumber((string) $receivedDropOffPoint['number'])
            ->setNumberSuffix($receivedDropOffPoint['number_suffix'] ?? null)
            ->setPostalCode($receivedDropOffPoint['postal_code'])
            ->setRegion($receivedDropOffPoint['region'] ?? null)
            ->setRetailNetworkId($receivedDropOffPoint['retail_network_id'] ?? null)
            ->setState($receivedDropOffPoint['state'] ?? null)
            ->setStreet($receivedDropOffPoint['street']);
    }

    /**
     * @return string|null
     */
    public function getBoxNumber(): ?string
    {
        return $this->box_number;
    }

    /**
     * @return string|null
     */
    public function getCc(): ?string
    {
        return $this->cc;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getLocationCode(): string
    {
        return $this->location_code;
    }

    /**
     * @return string
     */
    public function getLocationName(): string
    {
        return $this->location_name;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string|null
     */
    public function getNumberSuffix(): ?string
    {
        return $this->number_suffix;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @return string|null
     */
    public function getRetailNetworkId(): ?string
    {
        return $this->retail_network_id;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param  string|null $boxNumber
     *
     * @return self
     */
    public function setBoxNumber(?string $boxNumber = null): self
    {
        $this->box_number = $boxNumber;
        return $this;
    }

    /**
     * @param  string|null $cc
     *
     * @return self
     */
    public function setCc(?string $cc = null): self
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @param  string $city
     *
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param  string $locationCode
     *
     * @return self
     */
    public function setLocationCode(string $locationCode): self
    {
        $this->location_code = $locationCode;
        return $this;
    }

    /**
     * @param  string $locationName
     *
     * @return self
     */
    public function setLocationName(string $locationName): self
    {
        $this->location_name = $locationName;
        return $this;
    }

    /**
     * @param  string $number
     *
     * @return self
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param  string|null $numberSuffix
     *
     * @return self
     */
    public function setNumberSuffix(?string $numberSuffix = null): self
    {
        $this->number_suffix = $numberSuffix;
        return $this;
    }

    /**
     * @param  string $postalCode
     *
     * @return self
     */
    public function setPostalCode(string $postalCode): self
    {
        $this->postal_code = $postalCode;
        return $this;
    }

    /**
     * @param  string|null $region
     *
     * @return self
     */
    public function setRegion(?string $region = null): self
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @param  string|null $retailNetworkId
     *
     * @return self
     */
    public function setRetailNetworkId(?string $retailNetworkId = null): self
    {
        $this->retail_network_id = $retailNetworkId;
        return $this;
    }

    /**
     * @param  string|null $state
     *
     * @return self
     */
    public function setState(?string $state = null): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param  string $street
     *
     * @return self
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }
}
