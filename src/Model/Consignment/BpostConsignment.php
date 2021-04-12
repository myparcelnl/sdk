<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Exception\InvalidConsignmentException;

class BpostConsignment extends AbstractConsignment
{
    /**
     * @var int
     */
    public const CARRIER_ID = 2;

    /**
     * @var string
     */
    public const CARRIER_NAME = 'bpost';

    /**
     * @var int
     */
    public const DEFAULT_WEIGHT = 50;

    /**
     * @var array
     */
    public const INSURANCE_POSSIBILITIES_LOCAL = [0, 500, 1000, 1500, 2000];

    /**
     * @var array
     */
    private const VALID_PACKAGE_TYPES = [
        self::PACKAGE_TYPE_PACKAGE
    ];

    /**
     * @internal
     *
     * @var int
     */
    public $physical_properties = ['weight' => self::DEFAULT_WEIGHT];

    /**
     * @var string
     */
    protected $local_cc = self::CC_BE;

    /**
     * @param array $consignmentEncoded
     *
     * @return array
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
                        'box_number'             => (string) $this->getBoxNumber(),
                        'number_suffix'          => (string) $this->getNumberSuffix(),
                    ],
                ]
            );

            return $consignmentEncoded;
        }

        if ($this->getCountry() == self::CC_NL) {
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
     * @param string|null $numberSuffix
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
        return parent::setDeliveryDate(null);
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
            throw new \BadMethodCallException('Insurance must be one of ' . implode(', ', self::INSURANCE_POSSIBILITIES_LOCAL));
        }

        return parent::setInsurance($insurance);
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
     * @return string
     * @deprecated Use setRetailNetworkId instead
     *
     */
    public function getPickupNetworkId(): string
    {
        return $this->getRetailNetworkId();
    }

    /**
     * @return string
     */
    public function getRetailNetworkId(): string
    {
        return "";
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $retailNetworkId
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @deprecated Use setRetailNetworkId instead
     *
     */
    public function setPickupNetworkId($retailNetworkId): AbstractConsignment
    {
        return $this->setRetailNetworkId((string) $retailNetworkId);
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $retailNetworkId
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setRetailNetworkId(string $retailNetworkId): AbstractConsignment
    {
        $this->retail_network_id = $retailNetworkId;

        return $this;
    }

    /**
     * @return bool
     * @throws \MyParcelNL\Sdk\src\Exception\InvalidConsignmentException
     */
    public function validate(): bool
    {
        if ($this->getTotalWeight() < 1) {
            throw new InvalidConsignmentException('It is necessary to at a minimum weight of 1 grams');
        }

        /** @var \MyParcelNL\Sdk\src\Model\MyParcelCustomsItem $item */
        $totalValue = 0;
        foreach ((array) $this->items as $item) {
            $totalValue += $item->getItemValue();
        }

        if (! empty($totalValue) && $totalValue < 100) {
            throw new InvalidConsignmentException('It is necessary to use cents and the a minimum price is 1 euro');
        }

        return parent::validate();
    }
}
