<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RatesPostRatesRequestV1DataRatesItemOptions
{
    /**
     * Requires age verification option
     *
     * @var mixed
     */
    public $ageCheck;
    /**
     * Cash on delivery option
     *
     * @var RatesPostRatesRequestV1DataRatesItemOptionsCashOnDelivery
     */
    public RatesPostRatesRequestV1DataRatesItemOptionsCashOnDelivery $cashOnDelivery;
    /**
     * Scheduled collection option
     *
     * @var mixed
     */
    public $collect;
    /**
     * Cooled delivery option
     *
     * @var mixed
     */
    public $cooledDelivery;
    /**
     * Deliver at postal point option
     *
     * @var mixed
     */
    public $dropOffAtPostalPoint;
    /**
     * Additional insurance option
     *
     * @var mixed
     */
    public $extraAssurance;
    /**
     * Hide sender option
     *
     * @var mixed
     */
    public $hideSender;
    /**
     * Oversized package option
     *
     * @var mixed
     */
    public $largeFormat;
    /**
     * Ship without track and trace option
     *
     * @var mixed
     */
    public $noTracking;
    /**
     * Recipient only delivery option
     *
     * @var mixed
     */
    public $onlyRecipient;
    /**
     * Priority delivery option
     *
     * @var mixed
     */
    public $priorityDelivery;
    /**
     * Requires receipt code option
     *
     * @var mixed
     */
    public $receiptCode;
    /**
     * Return on first failed delivery option
     *
     * @var mixed
     */
    public $return;
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
     * Requires signature option
     *
     * @var mixed
     */
    public $signature;
    /**
     * DEPRECATED: replaced by no_tracking (its inverse).
     *
     * @var mixed
     */
    public $tracked;
    /**
     * Insurance option. Provide the insured amount as flat {amount, currency} (recommended) or via the deprecated nested {insured_amount: Money} form.
     *
     * @var mixed
     */
    public $insurance;
}