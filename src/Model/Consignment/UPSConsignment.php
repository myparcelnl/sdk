<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierUPS;
use MyParcelNL\Sdk\src\Validator\Consignment\UPSConsignmentValidator;

class UPSConsignment extends AbstractConsignment
{
    public const DEFAULT_WEIGHT = 3000;

    /**
     * @var int
     */
    public const PERSON_NAME_MAX_LENGTH = 35;

    /**
     * @internal
     * @var int
     */
    public $physical_properties = ['weight' => self::DEFAULT_WEIGHT];

    /**
     * @var string
     */
    protected $carrierClass = CarrierUPS::class;

    /**
     * @var string
     */
    protected $validatorClass = UPSConsignmentValidator::class;

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
    public function getAllowedPackageTypes(): array
    {
        return [
            self::PACKAGE_TYPE_PACKAGE_NAME,
        ];
    }

    /**
     * @return array|string[]
     */
    public function getAllowedDeliveryTypes(): array
    {
        return [
            self::DELIVERY_TYPE_STANDARD,
        ];
    }
}
