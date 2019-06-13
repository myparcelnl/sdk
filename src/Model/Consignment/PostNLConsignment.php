<?php

namespace MyparcelNL\Sdk\src\Model;


class PostNLConsignment extends AbstractConsignment
{
    public const CARRIER_ID = 1;

    /**
     * @var array
     */
    protected $insurance_possibilities_local = [0, 100, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000];

    /**
     * @var string
     */
    protected $local_cc = self::CC_NL;

    /**
     * @return boolean
     */
    public function isLargeFormat(): bool
    {
        return (bool) $this->large_format;
    }

    /**
     * Large format package
     *
     * Required: No
     *
     * @param boolean $largeFormat
     *
     * @return \MyparcelNL\Sdk\src\Model\PostNLConsignment
     * @throws \Exception
     */
    public function setLargeFormat(bool $largeFormat): AbstractConsignment
    {
        $this->large_format = $this->canHaveOption($largeFormat);

        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasAgeCheck(): bool
    {
        return (bool) $this->age_check;
    }

    /**
     * Age check
     *
     * Required: No
     *
     * @param bool $ageCheck
     *
     * @return $this
     * @throws \Exception
     */
    public function setAgeCheck(bool $ageCheck): AbstractConsignment
    {
        $this->age_check = $this->canHaveOption($ageCheck);

        return $this;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $pickupNetworkId
     *
     * @return \MyparcelNL\Sdk\src\Model\PostNLConsignment
     */
    public function setPickupNetworkId($pickupNetworkId): AbstractConsignment
    {
        $this->pickup_network_id = $pickupNetworkId;

        return $this;
    }

}