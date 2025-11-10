<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierTrunkrs;
use MyParcelNL\Sdk\Validator\Consignment\TrunkrsConsignmentValidator;

class TrunkrsConsignment extends AbstractConsignment
{
    /**
     * @var int
     */
    public const LABEL_DESCRIPTION_MAX_LENGTH = 45;

    /**
     * @var string
     */
    protected $carrierClass = CarrierTrunkrs::class;

    /**
     * @var string
     */
    protected $validatorClass = TrunkrsConsignmentValidator::class;

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
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedExtraOptions(): array
    {
        return [
            self::EXTRA_OPTION_DELIVERY_DATE,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptions(): array
    {
        return [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_RECEIPT_CODE,
            self::SHIPMENT_OPTION_FRESH_FOOD,
            self::SHIPMENT_OPTION_FROZEN,
            self::SHIPMENT_OPTION_SAME_DAY_DELIVERY,
            self::SHIPMENT_OPTION_SIGNATURE,
        ];
    }

    /**
     * @return array
     */
    public function getMandatoryShipmentOptions(): array
    {
        $mandatory = [];

        if ($this->hasReceiptCode() || $this->hasAgeCheck()) {
            $mandatory = array_merge($mandatory, [
                self::SHIPMENT_OPTION_SIGNATURE,
                self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            ]);
        }

        return $mandatory;
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptionsForPickup(): array
    {
        return [];
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
        return [];
    }

    /**
     * @return int[]
     */
    protected function getEuInsurancePossibilities(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getNlToBeInsurancePossibilities(): array
    {
        return [];
    }

    /**
     * @return int[]
     */
    protected function getRowInsurancePossibilities(): array
    {
        return [];
    }
}
