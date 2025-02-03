<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Consignment;

use MyParcelNL\Sdk\Model\Carrier\CarrierBpost;
use MyParcelNL\Sdk\Validator\Consignment\BpostConsignmentValidator;

class BpostConsignment extends AbstractConsignment
{
    protected const CARRIER_ID     = 2;
    protected const CARRIER_NAME   = 'bpost';

    /**
     * @var int
     */
    public const CUSTOMS_DECLARATION_DESCRIPTION_MAX_LENGTH = 30;

    /**
     * @var string
     */
    protected $carrierClass = CarrierBpost::class;

    /**
     * @var string
     */
    protected $validatorClass = BpostConsignmentValidator::class;

    /**
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return self::CC_BE;
    }

    /**
     * @return string[]
     */
    public function getAllowedExtraOptions(): array
    {
        return [
            self::EXTRA_OPTION_DELIVERY_SATURDAY,
            self::EXTRA_OPTION_MULTI_COLLO,
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
        ];
    }

    /**
     * @return int[]
     */
    protected function getLocalInsurancePossibilities(): array
    {
        return [500, 1000, 1500, 2000];
    }
}
