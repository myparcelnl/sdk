<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLForYou;
use MyParcelNL\Sdk\src\Validator\Consignment\DHLForYouConsignmentValidator;

class DHLForYouConsignment extends AbstractConsignment
{
    /** @deprecated use $this->getCarrierId() */
    public const CARRIER_ID = CarrierDHLForYou::ID;
    /** @deprecated use $this->getCarrierName() */
    public const CARRIER_NAME = CarrierDHLForYou::NAME;
    /**
     * @var array
     * @deprecated use getLocalInsurancePossibilities()
     */
    public const INSURANCE_POSSIBILITIES_LOCAL = [
        100,
    ];

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
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedExtraOptions(): array
    {
        return [
            self::EXTRA_OPTION_DELIVERY_MONDAY,
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
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_HIDE_SENDER,
            self::SHIPMENT_OPTION_EXTRA_ASSURANCE,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_SAME_DAY_DELIVERY,
            self::SHIPMENT_OPTION_SIGNATURE,
        ];
    }

    /**
     * @return string[]
     */
    protected function getAllowedShipmentOptionsForPickup(): array
    {
        return [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_EXTRA_ASSURANCE,
            self::SHIPMENT_OPTION_SIGNATURE,
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
            100,
        ];
    }
}
