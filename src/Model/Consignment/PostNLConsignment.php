<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

class PostNLConsignment extends AbstractConsignment
{
    /**
     * Carrier types
     * @var int
     */
    public const CARRIER_ID = 1;

    /**
     * @var array
     */
    private const VALID_PACKAGE_TYPES = [
        self::PACKAGE_TYPE_PACKAGE,
        self::PACKAGE_TYPE_MAILBOX,
        self::PACKAGE_TYPE_LETTER,
        self::PACKAGE_TYPE_DIGITAL_STAMP
    ];

    /**
     * @var array
     */
    protected $insurance_possibilities_local = [0, 100, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000];

    /**
     * @var string
     */
    protected $local_cc = self::CC_NL;


    /**
     * The id of the consignment
     *
     * Save this id in your database
     *
     * @deprecated Use getConsignmentId instead
     *
     * @return int
     */
    public function getMyParcelConsignmentId(): int
    {
        return $this->getConsignmentId();
    }

    /**
     * @internal
     *
     * The id of the consignment
     *
     * @deprecated Use getConsignmentId instead
     *
     * @param int $id
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setMyParcelConsignmentId(int $id): AbstractConsignment
    {
        return $this->setConsignmentId($id);
    }

    /**
     * @param array $consignmentEncoded
     *
     * @return array
     */
    public function encodeStreet(array $consignmentEncoded): array
    {
        if ($this->getCountry() == $this->local_cc) {
            $consignmentEncoded = array_merge_recursive(
                $consignmentEncoded,
                [
                    'recipient' => [
                        'street'                 => $this->getStreet(true),
                        'street_additional_info' => $this->getStreetAdditionalInfo(),
                        'number'                 => $this->getNumber(),
                        'number_suffix'          => (string) $this->getNumberSuffix(),
                    ],
                ]
            );

            return $consignmentEncoded;
        }

        return parent::encodeStreet($consignmentEncoded);
    }

    /**
     * Street number suffix.
     *
     * Required: no
     *
     * @param string $numberSuffix
     *
     * @return $this
     */
    public function setNumberSuffix(?string $numberSuffix): AbstractConsignment
    {
        $this->number_suffix = $numberSuffix;

        return $this;
    }

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
     * @param int $packageType
     *
     * @return $this
     * @throws \Exception
     */
    public function setPackageType(int $packageType): AbstractConsignment
    {
        if (! in_array($packageType, self::VALID_PACKAGE_TYPES)) {
            throw new \Exception('Use the correct package type for shipment:' . $this->consignment_id);
        }

        return parent::setPackageType($packageType);
    }

    /**
     * The delivery type for the package
     *
     * Required: Yes if delivery_date has been specified
     *
     * @param int  $deliveryType
     * @param bool $needDeliveryDate
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \Exception
     */
    public function setDeliveryType(int $deliveryType, bool $needDeliveryDate = true): AbstractConsignment
    {
        if ($needDeliveryDate &&
            $deliveryType !== self::DELIVERY_TYPE_STANDARD &&
            $this->getDeliveryDate() == null
        ) {
            throw new \Exception('If delivery type !== 2, first set delivery date with setDeliveryDate() before running setDeliveryType() for shipment: ' . $this->consignment_id);
        }

        return parent::setDeliveryType($deliveryType, $needDeliveryDate);
    }

    /**
     * The delivery date time for this shipment
     * Pattern: YYYY-MM-DD | YYYY-MM-DD HH:MM:SS
     * Example: 2017-01-01 | 2017-01-01 00:00:00
     * Required: Yes if delivery type has been specified
     *
     * @param string $delivery_date
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \Exception
     */
    public function setDeliveryDate(?string $delivery_date): AbstractConsignment
    {
        if (! $delivery_date) {
            throw new \BadMethodCallException('First set delivery date before running setDeliveryDate() for shipment: ' . $this->consignment_id);
        }

        return parent::setDeliveryDate($delivery_date);
    }

    /**
     * Insurance price for the package.
     *
     * Composite type containing integer and currency. The amount is without decimal separators.
     * Required: No
     *
     * @param int|null $insurance
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \Exception
     */
    public function setInsurance(?int $insurance): AbstractConsignment
    {
        if (null === $insurance) {
            throw new \BadMethodCallException('Insurance must be one of ' . implode(', ', $this->insurance_possibilities_local));
        }

        return parent::setInsurance($insurance);
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
     *  * Large format package
     *
     * Required: No
     *
     * @param bool $largeFormat
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPickupNetworkId($pickupNetworkId): AbstractConsignment
    {
        $this->pickup_network_id = $pickupNetworkId;

        return $this;
    }
}