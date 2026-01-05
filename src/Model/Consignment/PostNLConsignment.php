<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Validator\Consignment\PostNLConsignmentValidator;

class PostNLConsignment extends AbstractConsignment
{
    /**
     * @var string
     */
    protected $carrierClass = CarrierPostNL::class;

    /**
     * @var string
     */
    protected $validatorClass = PostNLConsignmentValidator::class;

    /**
     * @return string[]
     */
    public function getAllowedDeliveryTypes(): array
    {
        return [
            self::DELIVERY_TYPE_MORNING_NAME,
            self::DELIVERY_TYPE_STANDARD_NAME,
            self::DELIVERY_TYPE_EVENING_NAME,
            self::DELIVERY_TYPE_PICKUP_NAME,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedExtraOptions(): array
    {
        return [
            self::EXTRA_OPTION_DELIVERY_DATE,
            self::EXTRA_OPTION_DELIVERY_MONDAY,
            self::EXTRA_OPTION_MULTI_COLLO,
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
            self::PACKAGE_TYPE_LETTER_NAME,
            self::PACKAGE_TYPE_DIGITAL_STAMP_NAME,
            self::PACKAGE_TYPE_PACKAGE_SMALL_NAME,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptions(): array
    {
        if ($this->hasReceiptCode()) {
            return [
                self::SHIPMENT_OPTION_INSURANCE,
                self::SHIPMENT_OPTION_RECEIPT_CODE,
            ];
        }
        return [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_LARGE_FORMAT,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_PRINTERLESS_RETURN,
            self::SHIPMENT_OPTION_RETURN,
            self::SHIPMENT_OPTION_SIGNATURE,
            self::SHIPMENT_OPTION_RECEIPT_CODE,
            self::SHIPMENT_OPTION_PRIORITY_DELIVERY,
        ];
    }

    /**
     * @return array
     */
    public function getMandatoryShipmentOptions(): array
    {
        $mandatory = [];

        if ($this->hasReceiptCode()) {
            $mandatory[] = self::SHIPMENT_OPTION_INSURANCE;
        } elseif ($this->hasAgeCheck()) {
            $mandatory = array_merge($mandatory, [
                self::SHIPMENT_OPTION_ONLY_RECIPIENT,
                self::SHIPMENT_OPTION_SIGNATURE,
            ]);
        }

        return $mandatory;
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptionsForPickup(): array
    {
        return [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_LARGE_FORMAT,
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_SIGNATURE,
        ];
    }

    /**
     * @param string $option
     *
     * @return bool
     */
    public function canHaveShipmentOption(string $option): bool
    {
        // Priority delivery is ONLY allowed for mailbox (BBP) package type
        if (self::SHIPMENT_OPTION_PRIORITY_DELIVERY === $option) {
            return self::PACKAGE_TYPE_MAILBOX === $this->getPackageType();
        }

        return parent::canHaveShipmentOption($option);
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

    /**
     * @return int[]
     */
    protected function getEuInsurancePossibilities(): array
    {
        return [
            50,
            500,
        ];
    }


    /**
     * @return array
     */
    protected function getNlToBeInsurancePossibilities(): array
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

    /**
     * @return int[]
     */
    protected function getRowInsurancePossibilities(): array
    {
        return [
            50,
            500,
        ];
    }
}
