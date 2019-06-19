<?php

namespace MyparcelNL\Sdk\src\Model;


use MyParcelNL\Sdk\src\Helper\SplitStreet;

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
     * The package type
     *
     * For international shipment only package type 1 is allowed
     * Pattern: [1 – 3]<br>
     * Example:
     *          1. package
     *          2. mailbox package
     *          3. letter
     * Required: Yes
     *
     * @param int $package_type
     *
     * @return $this
     * @throws \Exception
     */
    public function setPackageType(int $package_type): AbstractConsignment
    {
        if ($package_type != self::PACKAGE_TYPE_PACKAGE &&
            $package_type != self::PACKAGE_TYPE_MAILBOX &&
            $package_type != self::PACKAGE_TYPE_LETTER
        ) {
            throw new \Exception('Use the correct package type for shipment:' . $this->myparcel_consignment_id);
        }

        return parent::setPackageType($package_type);
    }


    /**
     * The delivery type for the package
     *
     * Required: Yes if delivery_date has been specified
     *
     * @param int  $deliveryType
     * @param bool $needDeliveryDate
     *
     * @return \MyParcelNL\Sdk\src\Model\AbstractConsignment
     * @throws \Exception
     */
    public function setDeliveryType(int $deliveryType, $needDeliveryDate = true): AbstractConsignment
    {
        if ($needDeliveryDate &&
            $deliveryType !== self::DELIVERY_TYPE_STANDARD &&
            $this->getDeliveryDate() == null
        ) {
            throw new \Exception('If delivery type !== 2, first set delivery date with setDeliveryDate() before running setDeliveryType() for shipment: ' . $this->myparcel_consignment_id);
        }

        return parent::setDeliveryType($deliveryType);
    }


    /**
     * @return bool
     */
    public function isOnlyRecipient(): bool
    {
        return (bool) $this->only_recipient;
    }

    /**
     * Deliver the package to the recipient only
     *
     * Required: No
     *
     * @param bool $only_recipient
     *
     * @return $this
     * @throws \Exception
     */
    public function setOnlyRecipient(bool $only_recipient): AbstractConsignment
    {
        $this->only_recipient = $this->canHaveOption($only_recipient);

        return $this;
    }

    /**
     * @return bool
     */
    public function isSignature(): bool
    {
        return (bool) $this->signature;
    }

    /**
     * Package must be signed for
     *
     * Required: No
     *
     * @param bool $signature
     *
     * @return $this
     * @throws \Exception
     */
    public function setSignature(bool $signature): AbstractConsignment
    {
        $this->signature = $this->canHaveOption($signature);

        return $this;
    }

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