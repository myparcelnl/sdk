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
        return [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_HIDE_SENDER,
            self::SHIPMENT_OPTION_EXTRA_ASSURANCE,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_SIGNATURE,
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
