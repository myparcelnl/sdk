<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptions
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
     * @var mixed
     */
    public $cashOnDelivery;
    /**
     * Scheduled collection option
     *
     * @var mixed
     */
    public $collect;
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
     * Fresh food delivery option
     *
     * @var mixed
     */
    public $freshFood;
    /**
     * Frozen delivery option
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
     * Printerless return delivery option
     *
     * @var mixed
     */
    public $printerlessReturn;
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
     * Insurance option. Empty object selects the carrier-default coverage. A Money object with amount > 0 requests a specific coverage in minor units; amount 0 (or empty currency) is treated as no insurance requested.
     *
     * @var mixed
     */
    public $insurance;
}