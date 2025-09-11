<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierGLS;
use MyParcelNL\Sdk\Validator\Consignment\GLSConsignmentValidator;

class GLSConsignment extends AbstractConsignment
{
    /**
     * @var string
     */
    protected $carrierClass = CarrierGLS::class;

    /**
     * @var string
     */
    protected $validatorClass = GLSConsignmentValidator::class;

    /**
     * @return string[]
     */
    public function getAllowedDeliveryTypes(): array
    {
        return [
            self::DELIVERY_TYPE_STANDARD_NAME,
            self::DELIVERY_TYPE_PICKUP_NAME,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedPackageTypes(): array
    {
        return [
            self::PACKAGE_TYPE_PACKAGE_NAME,
            self::PACKAGE_TYPE_MAILBOX_NAME,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedExtraOptions(): array
    {
        return [
            self::EXTRA_OPTION_DELIVERY_SATURDAY,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptions(): array
    {
        return [
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_SIGNATURE,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptionsForPickup(): array
    {
        return [
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_SIGNATURE,
        ];
    }

    /**
     * @return array
     */
    public function getMandatoryShipmentOptions(): array
    {
        $mandatory = [];

        if ($this->getCountry() && $this->getCountry() !== self::CC_NL) {
            $mandatory[] = self::SHIPMENT_OPTION_SIGNATURE;
        }

        return $mandatory;
    }

    /**
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return self::CC_NL;
    }

    /**
     * @return int[]
     */
    protected function getLocalInsurancePossibilities(): array
    {
        return [10000];
    }

    /**
     * @return int[]
     */
    protected function getEuInsurancePossibilities(): array
    {
        return [10000];
    }

    /**
     * @return int[]
     */
    protected function getRowInsurancePossibilities(): array
    {
        return [10000];
    }

    /**
     * Override insurance getter to always return 10000 (100 euro) as it's standard included
     *
     * @return int
     */
    public function getInsurance(): int
    {
        return max(10000, parent::getInsurance());
    }

    /**
     * Override setCountry to automatically enable signature for non-NL countries
     *
     * @param string $cc
     * @return self
     */
    public function setCountry(string $cc): self
    {
        parent::setCountry($cc);
        
        // Buiten NL is handtekening altijd verplicht
        if ($cc !== self::CC_NL) {
            $this->setSignature(true);
        }
        
        return $this;
    }

    /**
     * Check if Saturday delivery is only available for NL
     *
     * @param string $option
     * @return bool
     */
    public function canHaveExtraOption(string $option): bool
    {
        if ($option === self::EXTRA_OPTION_DELIVERY_SATURDAY) {
            return $this->getCountry() === self::CC_NL;
        }

        return parent::canHaveExtraOption($option);
    }

    /**
     * Check if signature can be turned off (only in NL)
     *
     * @param string $option
     * @return bool
     */
    public function canHaveShipmentOption(string $option): bool
    {
        if ($option === self::SHIPMENT_OPTION_SIGNATURE) {
            return true;
        }

        return parent::canHaveShipmentOption($option);
    }
}
