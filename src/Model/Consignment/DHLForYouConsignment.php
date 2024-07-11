<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLForYou;
use MyParcelNL\Sdk\src\Validator\Consignment\DHLForYouConsignmentValidator;

class DHLForYouConsignment extends AbstractConsignment
{
    /**
     * @var string
     */
    protected $carrierClass = CarrierDHLForYou::class;

    /**
     * @var string
     */
    protected $validatorClass = DHLForYouConsignmentValidator::class;

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
    public function getAllowedShipmentOptions(): array
    {
        $allowed = [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_HIDE_SENDER,
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_SIGNATURE,
            self::SHIPMENT_OPTION_SAME_DAY_DELIVERY,
        ];

        if (! $this->hasAgeCheck()) {
            $allowed[] = self::SHIPMENT_OPTION_ONLY_RECIPIENT;
        }

        return $allowed;
    }

    /**
     * @return array|string[]
     */
    public function getAllowedShipmentOptionsForPickup(): array
    {
        return [
            self::SHIPMENT_OPTION_INSURANCE,
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
     * @return array
     */
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
            100,
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
}
