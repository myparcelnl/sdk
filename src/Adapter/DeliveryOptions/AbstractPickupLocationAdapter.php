<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

class AbstractPickupLocationAdapter
{
    /**
     * @var string
     */
    protected $location_name;

    /**
     * @var string
     */
    protected $location_code;

    /**
     * @var string|null
     */
    protected $retail_network_id;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $postal_code;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $cc;

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
    public function getLocationCode(): string
    {
        return $this->location_code;
    }

    /**
     * @return string|null
     * @deprecated Use getRetailNetworkId instead
     */
    public function getPickupNetworkId(): ?string
    {
        return $this->getRetailNetworkId();
    }

    /**
     * @return string|null
     */
    public function getRetailNetworkId(): ?string
    {
        return $this->retail_network_id;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postal_code;
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
    public function getCountry(): ?string
    {
        return $this->cc;
    }

    /**
     * @param  string $cc
     */
    public function setCountry(string $cc): void
    {
        $this->cc = $cc;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'location_name'     => $this->getLocationName(),
            'location_code'     => $this->getLocationCode(),
            'retail_network_id' => $this->getRetailNetworkId(),
            'street'            => $this->getStreet(),
            'number'            => $this->getNumber(),
            'postal_code'       => $this->getPostalCode(),
            'city'              => $this->getCity(),
            'cc'                => $this->getCountry(),
        ];
    }
}
