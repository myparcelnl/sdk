<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierUPS;
use MyParcelNL\Sdk\Validator\Consignment\UPSConsignmentValidator;

class UPSConsignment extends AbstractConsignment
{
    public const DEFAULT_WEIGHT = 3000;

    /**
     * @var int
     */
    public const PERSON_NAME_MAX_LENGTH = 35;

    /**
     * @internal
     * @var int
     */
    public $physical_properties = ['weight' => self::DEFAULT_WEIGHT];

    /**
     * @var string
     */
    protected $carrierClass = CarrierUPS::class;

    /**
     * @var string
     */
    protected $validatorClass = UPSConsignmentValidator::class;

    /**
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return self::CC_NL;
    }

    /**
     * @return array|string[]
     */
    public function getAllowedPackageTypes(): array
    {
        return [
            self::PACKAGE_TYPE_PACKAGE_NAME,
        ];
    }

    /**
     * @return array|string[]
     */
    public function getAllowedDeliveryTypes(): array
    {
        return [
            self::DELIVERY_TYPE_STANDARD_NAME,
            self::DELIVERY_TYPE_EXPRESS_NAME,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptions(): array
    {
        return [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_COLLECT,
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_SIGNATURE,
        ];
    }

    /**
     * @return string[]
     */
    public function getMandatoryShipmentOptions(): array
    {
        if ($this->hasAgeCheck()) {
            return [
                self::SHIPMENT_OPTION_SIGNATURE,
            ];
        }

        return [];
    }

    /**
     * @return int[]
     */
    protected function getLocalInsurancePossibilities(): array
    {
        return [
            250,
            500,
            1000,
            1500,
            2000,
            2500,
            3000,
            3500,
            4000,
            4500,
            5000,
        ];
    }

    /**
     * @return int[]
     */
    protected function getEuInsurancePossibilities(): array
    {
        return $this->getLocalInsurancePossibilities();
    }

    /**
     * @return array
     */
    protected function getNlToBeInsurancePossibilities(): array
    {
        return $this->getLocalInsurancePossibilities();
    }
}
