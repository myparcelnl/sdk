<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Validator\Consignment\RedJePakketjeConsignmentValidator;

class RedJePakketjeConsignment extends AbstractConsignment
{
    /** @deprecated use $this->getCarrierId() */
    public const CARRIER_ID = 5;
    /** @deprecated use $this->getCarrierName() */
    public const CARRIER_NAME = 'redjepakketje';

    /**
     * @var string
     */
    protected $carrierClass = CarrierRedJePakketje::class;

    /**
     * @var string
     */
    protected $validatorClass = RedJePakketjeConsignmentValidator::class;

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
    public function getAllowedShipmentOptions(): array
    {
        return [
            self::SHIPMENT_OPTION_AGE_CHECK,
            self::SHIPMENT_OPTION_LARGE_FORMAT,
            self::SHIPMENT_OPTION_ONLY_RECIPIENT,
            self::SHIPMENT_OPTION_RETURN,
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
        ];
    }
}
