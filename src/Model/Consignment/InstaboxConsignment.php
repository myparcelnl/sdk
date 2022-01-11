<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierInstabox;
use MyParcelNL\Sdk\src\Validator\Consignment\InstaboxConsignmentValidator;

class InstaboxConsignment extends AbstractConsignment
{
    /** @deprecated use $this->getCarrierId() */
    public const CARRIER_ID = 5;
    /** @deprecated use $this->getCarrierName() */
    public const CARRIER_NAME = 'instabox';

    /**
     * @var string
     */
    protected $carrierClass = CarrierInstabox::class;

    /**
     * @var string
     */
    protected $validatorClass = InstaboxConsignmentValidator::class;

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
            self::EXTRA_OPTION_DELIVERY_DATE,
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
            self::SHIPMENT_OPTION_LARGE_FORMAT,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_RETURN,
            self::SHIPMENT_OPTION_SAME_DAY_DELIVERY,
        ];
    }

    /**
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return self::CC_NL;
    }
}
