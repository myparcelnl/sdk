<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentPostShipmentsRequestDataShipmentsItemOptions implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefTypesPriceEuro
     */
    public RefTypesPriceEuro $insurance;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $ageCheck;
    /**
     * Money amount with currency
     *
     * @var RefTypesMoney
     */
    public RefTypesMoney $cashOnDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $collect;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $cooledDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $dropOffAtPostalPoint;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $extraAssurance;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $freshFood;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $frozen;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $hideSender;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $largeFormat;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $noTracking;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $onlyRecipient;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $priorityDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $receiptCode;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $return;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $sameDayDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $saturdayDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $signature;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $tracked;
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
    /**
     * The Incoterms® that apply to the Shipment.
     * 
     * When omitted, the default rule of the carrier applies.
     * 
     *
     * @var RefShipmentIncoterms
     */
    public RefShipmentIncoterms $incoterms;
    public function definedProperties(): array
    {
        return ['insurance' => 'insurance', 'ageCheck' => 'age_check', 'cashOnDelivery' => 'cash_on_delivery', 'collect' => 'collect', 'cooledDelivery' => 'cooled_delivery', 'dropOffAtPostalPoint' => 'drop_off_at_postal_point', 'extraAssurance' => 'extra_assurance', 'freshFood' => 'fresh_food', 'frozen' => 'frozen', 'hideSender' => 'hide_sender', 'largeFormat' => 'large_format', 'noTracking' => 'no_tracking', 'onlyRecipient' => 'only_recipient', 'priorityDelivery' => 'priority_delivery', 'receiptCode' => 'receipt_code', 'return' => 'return', 'sameDayDelivery' => 'same_day_delivery', 'saturdayDelivery' => 'saturday_delivery', 'signature' => 'signature', 'tracked' => 'tracked', 'packageType' => 'package_type', 'deliveryType' => 'delivery_type', 'deliveryDate' => 'delivery_date', 'labelDescription' => 'label_description', 'incoterms' => 'incoterms'];
    }
}