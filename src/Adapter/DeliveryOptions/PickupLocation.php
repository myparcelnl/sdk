<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Adapter\DeliveryOptions;

/**
 * Class PickupLocation
 *
 * @package MyParcelNL\Sdk\src\Model\DeliveryOptions
 */
class PickupLocation
{
    /**
     * @var string
     */
    private $location_name;

    /**
     * @var string
     */
    private $location_code;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $postal_code;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $cc;

    /**
     * PickupLocation constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->location_name = $data["location_name"];
        $this->location_code = $data["location_code"];
        $this->street        = $data["street"];
        $this->number        = $data["number"];
        $this->postal_code   = $data["postal_code"];
        $this->city          = $data["city"];
        $this->cc            = $data["cc"];
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
    public function getLocationCode(): string
    {
        return $this->location_code;
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
     * @param string $cc
     */
    public function setCountry(string $cc): void
    {
        $this->cc = $cc;
    }
}
