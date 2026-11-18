<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RatesPostRatesRequestV2Options
{
    /**
     * Additional insurance option
     *
     * @var mixed
     */
    public $additionalInsurance;
    /**
     * Deliver at postal point option
     *
     * @var mixed
     */
    public $deliverAtPostalPoint;
    /**
     * Fresh food option
     *
     * @var mixed
     */
    public $freshFood;
    /**
     * Frozen option
     *
     * @var mixed
     */
    public $frozen;
    /**
     * Hide sender option
     *
     * @var mixed
     */
    public $hideSender;
    /**
     * Insurance option. Provide the insured amount as flat {amount, currency} (recommended) or via the deprecated nested {insuredAmount: Money} form.
     *
     * @var mixed
     */
    public $insurance;
    /**
     * Ship without track and trace option
     *
     * @var mixed
     */
    public $noTracking;
    /**
     * Oversized package option
     *
     * @var mixed
     */
    public $oversizedPackage;
    /**
     * Priority delivery package option
     *
     * @var mixed
     */
    public $priorityDelivery;
    /**
     * Recipient only delivery option
     *
     * @var mixed
     */
    public $recipientOnlyDelivery;
    /**
     * Return on first failed delivery option
     *
     * @var mixed
     */
    public $returnOnFirstFailedDelivery;
    /**
     * Requires age verification option
     *
     * @var mixed
     */
    public $requiresAgeVerification;
    /**
     * Cash on delivery option
     *
     * @var RatesPostRatesRequestV2OptionsRequiresCashOnDelivery
     */
    public RatesPostRatesRequestV2OptionsRequiresCashOnDelivery $requiresCashOnDelivery;
    /**
     * Requires receipt code option
     *
     * @var mixed
     */
    public $requiresReceiptCode;
    /**
     * Requires signature option
     *
     * @var mixed
     */
    public $requiresSignature;
    /**
     * Same day delivery option
     *
     * @var mixed
     */
    public $sameDayDelivery;
    /**
     * Saturday delivery option
     *
     * @var mixed
     */
    public $saturdayDelivery;
    /**
     * Scheduled collection option
     *
     * @var mixed
     */
    public $scheduledCollection;
}