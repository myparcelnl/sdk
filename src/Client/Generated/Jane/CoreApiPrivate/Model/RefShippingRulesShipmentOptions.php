<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class RefShippingRulesShipmentOptions
{
    /**
     * @var float
     */
    public float $ageCheck;
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
    public float $offPeakDiscount;
    /**
     * @var float
     */
    public float $onlyRecipient;
    /**
     * @var float
     */
    public float $printerlessReturn;
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
     * A monetary amount with its currency. Null when no amount applies; otherwise an object with the amount and currency fields, each independently nullable.
     *
     * @var mixed|null
     */
    public $contribution;
    /**
     * A monetary amount with its currency. Null when no amount applies; otherwise an object with the amount and currency fields, each independently nullable.
     *
     * @var mixed|null
     */
    public $insurance;
    /**
     * Free-form text to print on the shipment label.
     *
     * @var null|string
     */
    public ?string $labelDescription;
    /**
     * Package Type.
     *
     * @var RefShipmentPackageType|int
     */
    public RefShipmentPackageType|int $packageType;
}