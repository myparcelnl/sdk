<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierBpost;
use MyParcelNL\Sdk\src\Validator\Consignment\BpostConsignmentValidator;

class BpostConsignment extends AbstractConsignment
{
    /** @deprecated use $this->getCarrierId() */
    public const CARRIER_ID     = 2;

    /** @deprecated use $this->getCarrierName() */
    public const CARRIER_NAME   = 'bpost';

    public const DEFAULT_WEIGHT = 50;

    /**
     * @var int
     */
    public const CUSTOMS_DECLARATION_DESCRIPTION_MAX_LENGTH = 30;

    /**
     * @var array
     * @deprecated use getLocalInsurancePossibilities()
     */
    public const INSURANCE_POSSIBILITIES_LOCAL = [500, 1000, 1500, 2000];

    /**
     * @internal
     * @var int
     */
    public $physical_properties = ['weight' => self::DEFAULT_WEIGHT];

    /**
     * @var string
     */
    protected $carrierClass = CarrierBpost::class;

    /**
     * @var string
     */
    protected $validatorClass = BpostConsignmentValidator::class;

    /**
     * @param  array $consignmentEncoded
     *
     * @return array
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function encodeStreet(array $consignmentEncoded): array
    {
        if (self::CC_BE === $this->getCountry()) {
            return array_merge_recursive($consignmentEncoded, [
                'recipient' => [
                    'street'                 => $this->getStreet(true),
                    'street_additional_info' => $this->getStreetAdditionalInfo(),
                    'number'                 => $this->getNumber(),
                    'box_number'             => (string) $this->getBoxNumber(),
                    'number_suffix'          => (string) $this->getNumberSuffix(),
                ],
            ]);
        }

        return parent::encodeStreet($consignmentEncoded);
    }


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
