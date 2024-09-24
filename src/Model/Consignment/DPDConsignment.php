<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierDPD;
use MyParcelNL\Sdk\src\Validator\Consignment\DPDConsignmentValidator;

class DPDConsignment extends AbstractConsignment
{
    /**
     * @var array
     */
    public const ADDITIONAL_COUNTRY_COSTS = [
        'BA',
        'IS',
        'HR',
        'MC',
        'NO',
        'UA',
        'RS',
        'CH',
    ];

    /** @deprecated use $this->getCarrierId() */
    public const CARRIER_ID = 4;

    /** @deprecated use $this->getCarrierName() */
    public const CARRIER_NAME = 'dpd';

    /**
     * @var string
     */
    protected $carrierClass = CarrierDPD::class;

    /**
     * @var string
     */
    protected $validatorClass = DPDConsignmentValidator::class;

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
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return self::CC_BE;
    }
}
