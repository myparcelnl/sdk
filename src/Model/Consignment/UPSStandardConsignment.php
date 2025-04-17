<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierUPSStandard;

class UPSStandardConsignment extends AbstractConsignment
{
    protected const CARRIER_ID = 12;
    protected const CARRIER_NAME = 'ups_standard';

    /**
     * @var string
     */
    protected $carrierClass = CarrierUPSStandard::class;

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
    public function getAllowedDeliveryTypes(): array
    {
        return [
            self::DELIVERY_TYPE_STANDARD_NAME,
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
            self::SHIPMENT_OPTION_COLLECT,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::EXTRA_OPTION_DELIVERY_SATURDAY,
            self::SHIPMENT_OPTION_AGE_CHECK,
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
