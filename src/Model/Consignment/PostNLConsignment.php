<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Validator\Consignment\PostNLConsignmentValidator;

class PostNLConsignment extends AbstractConsignment
{
    /** @deprecated use $this->getCarrierId() */
    public const CARRIER_ID = 1;
    /** @deprecated use $this->getCarrierName() */
    public const CARRIER_NAME = 'postnl';
    /**
     * @var array
     * @deprecated use getLocalInsurancePossibilities()
     */
    public const INSURANCE_POSSIBILITIES_LOCAL = [
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

    /**
     * @var string
     */
    protected $carrierClass = CarrierPostNL::class;

    /**
     * @var string
     */
    protected $validatorClass = PostNLConsignmentValidator::class;

    /**
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return self::CC_NL;
    }

    /**
     * @return string[]
     */
    protected function getAllowedExtraOptions(): array
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
            self::SHIPMENT_OPTION_INSURANCE,
            self::SHIPMENT_OPTION_LARGE_FORMAT,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_RETURN,
            self::SHIPMENT_OPTION_SIGNATURE,
        ];
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
    protected function getValidPackageTypes(): array
    {
        return [
            self::PACKAGE_TYPE_PACKAGE,
            self::PACKAGE_TYPE_MAILBOX,
            self::PACKAGE_TYPE_LETTER,
            self::PACKAGE_TYPE_DIGITAL_STAMP,
        ];
    }
}
