<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

class DPDConsignment extends AbstractConsignment
{
    public const CARRIER_ID = 4;

    /**
     * @var array
     */
    protected $insurance_possibilities_local = [0];

    /**
     * @var string
     */
    protected $local_cc = self::CC_BE;

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
        if ($package_type != self::PACKAGE_TYPE_PACKAGE) {
            throw new \Exception('Use the correct package type for shipment:' . $this->consignment_id);
        }

        return parent::setPackageType($package_type);
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
        if (! $insurance) {
            throw new \BadMethodCallException('Insurance must be one of ' . implode(', ', $this->insurance_possibilities_local));
        }

        return parent::setDeliveryDate($insurance);
    }

    /**
     * @return string
     */
    public function getPickupNetworkId(): string
    {
        return $this->pickup_network_id;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $pickupNetworkId
     *
     * @return \MyParcelNL\Sdk\src\Model\PostNLConsignment
     */
    public function setPickupNetworkId($pickupNetworkId): AbstractConsignment
    {
        $this->pickup_network_id = $pickupNetworkId;

        return $this;
    }
}