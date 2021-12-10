<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

class PickupLocation extends BaseModel
{
    /**
     * @var string|null
     */
    private $cc;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $location_code;

    /**
     * @var string|null
     */
    private $location_name;

    /**
     * @var string|null
     */
    private $number;

    /**
     * @var string|null
     */
    private $number_suffix;

    /**
     * @var string|null
     */
    private $postal_code;

    /**
     * @var string|null
     */
    private $retail_network_id;

    /**
     * @var string|null
     */
    private $street;

    /**
     * @param  array $data
     */
    public function __construct(array $data = [])
    {
        $this->cc                = $data['cc'] ?? null;
        $this->city              = $data['city'] ?? null;
        $this->postal_code       = $data['postal_code'] ?? null;
        $this->street            = $data['street'] ?? null;
        $this->number            = $data['number'] ?? null;
        $this->number_suffix     = $data['number_suffix'] ?? null;
        $this->location_name     = $data['location_name'] ?? null;
        $this->location_code     = $data['location_code'] ?? null;
        $this->retail_network_id = $data['retail_network_id'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getCc(): ?string
    {
        return $this->cc;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return null|string
     */
    public function getLocationCode(): ?string
    {
        return $this->location_code;
    }

    /**
     * @return null|string
     */
    public function getLocationName(): ?string
    {
        return $this->location_name;
    }

    /**
     * @return null|string
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @return null|string
     */
    public function getNumberSuffix(): ?string
    {
        return $this->number_suffix;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    /**
     * @return null|string
     */
    public function getRetailNetworkId(): ?string
    {
        return $this->retail_network_id;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param  string|null $cc
     *
     * @return self
     */
    public function setCc(?string $cc): self
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @param  string|null $city
     *
     * @return self
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param  string|null $locationCode
     *
     * @return self
     */
    public function setLocationCode(?string $locationCode): self
    {
        $this->location_code = $locationCode;
        return $this;
    }

    /**
     * @param  string|null $locationName
     *
     * @return self
     */
    public function setLocationName(?string $locationName): self
    {
        $this->location_name = $locationName;
        return $this;
    }

    /**
     * @param  string|null $number
     *
     * @return self
     */
    public function setNumber(?string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param  string|null $numberSuffix
     *
     * @return self
     */
    public function setNumberSuffix(?string $numberSuffix): self
    {
        $this->number_suffix = $numberSuffix;
        return $this;
    }

    /**
     * @param  string|null $postalCode
     *
     * @return self
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postal_code = $postalCode;
        return $this;
    }

    /**
     * @param  string|null $retailNetworkId
     *
     * @return self
     */
    public function setRetailNetworkId(?string $retailNetworkId): self
    {
        $this->retail_network_id = $retailNetworkId;
        return $this;
    }

    /**
     * @param  string|null $street
     *
     * @return self
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'cc'                => $this->getCc(),
            'city'              => $this->getCity(),
            'street'            => $this->getStreet(),
            'number'            => $this->getNumber(),
            'number_suffix'     => $this->getNumberSuffix(),
            'postal_code'       => $this->getPostalCode(),
            'location_name'     => $this->getLocationName(),
            'location_code'     => $this->getLocationCode(),
            'retail_network_id' => $this->getRetailNetworkId(),
        ];
    }
}
