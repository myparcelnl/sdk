<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class CapabilitiesPostCapabilitiesRequestV2Options
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
     * Insurance option. Empty object selects the carrier-default coverage. A Money object with amount > 0 requests a specific coverage in minor units; amount 0 (or empty currency) is treated as no insurance requested.
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
     * Print return label at drop off package option
     *
     * @var mixed
     */
    public $printReturnLabelAtDropOff;
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
     * @var mixed
     */
    public $requiresCashOnDelivery;
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