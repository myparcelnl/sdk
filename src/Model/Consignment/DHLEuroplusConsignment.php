<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLEuroplus;
use MyParcelNL\Sdk\src\Validator\Consignment\DHLEuroplusConsignmentValidator;

class DHLEuroplusConsignment extends AbstractConsignment
{
    /**
     * @var string
     */
    protected $carrierClass = CarrierDHLEuroplus::class;

    /**
     * @var string
     */
    protected $validatorClass = DHLEuroplusConsignmentValidator::class;

    /**
     * @return string[]
     */
    public function getAllowedDeliveryTypes(): array
    {
        return [
            self::DELIVERY_TYPE_STANDARD_NAME,
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
    public function getAllowedShipmentOptions(): array
    {
        return [
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_SIGNATURE,
            self::EXTRA_OPTION_DELIVERY_SATURDAY
        ];
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
        return [
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
        return [
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

    protected function getNlToBeInsurancePossibilities(): array
    {
        return [
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
    protected function getRowInsurancePossibilities(): array
    {
        return [
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
}
