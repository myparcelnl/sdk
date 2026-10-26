<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentShipmentOptions implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefTypesPriceEuro
     */
    public RefTypesPriceEuro $insurance;
    /**
     * @var float
     */
    public float $ageCheck;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $cashOnDelivery;
    /**
     * @var float
     */
    public float $collect;
    /**
     * @var float
     */
    public float $cooledDelivery;
    /**
     * @var float
     */
    public float $dropOffAtPostalPoint;
    /**
     * @var float
     */
    public float $extraAssurance;
    /**
     * @var float
     */
    public float $freshFood;
    /**
     * @var float
     */
    public float $frozen;
    /**
     * @var float
     */
    public float $hideSender;
    /**
     * @var float
     */
    public float $largeFormat;
    /**
     * @var float
     */
    public float $noTracking;
    /**
     * @var float
     */
    public float $onlyRecipient;
    /**
     * @var float
     */
    public float $priorityDelivery;
    /**
     * @var float
     */
    public float $receiptCode;
    /**
     * @var float
     */
    public float $return;
    /**
     * @var float
     */
    public float $sameDayDelivery;
    /**
     * @var float
     */
    public float $saturdayDelivery;
    /**
     * @var float
     */
    public float $signature;
    /**
     * @var float
     */
    public float $tracked;
    /**
     * Package Type.
     *
     * @var RefShipmentPackageType|int
     */
    public RefShipmentPackageType|int $packageType;
    /**
     * @var int|null
     */
    public ?int $deliveryType;
    /**
     * @var string|null
     */
    public ?string $deliveryDate;
    /**
     * @var null|string
     */
    public ?string $labelDescription;
    public function definedProperties(): array
    {
        return ['insurance' => 'insurance', 'ageCheck' => 'age_check', 'cashOnDelivery' => 'cash_on_delivery', 'collect' => 'collect', 'cooledDelivery' => 'cooled_delivery', 'dropOffAtPostalPoint' => 'drop_off_at_postal_point', 'extraAssurance' => 'extra_assurance', 'freshFood' => 'fresh_food', 'frozen' => 'frozen', 'hideSender' => 'hide_sender', 'largeFormat' => 'large_format', 'noTracking' => 'no_tracking', 'onlyRecipient' => 'only_recipient', 'priorityDelivery' => 'priority_delivery', 'receiptCode' => 'receipt_code', 'return' => 'return', 'sameDayDelivery' => 'same_day_delivery', 'saturdayDelivery' => 'saturday_delivery', 'signature' => 'signature', 'tracked' => 'tracked', 'packageType' => 'package_type', 'deliveryType' => 'delivery_type', 'deliveryDate' => 'delivery_date', 'labelDescription' => 'label_description'];
    }
}