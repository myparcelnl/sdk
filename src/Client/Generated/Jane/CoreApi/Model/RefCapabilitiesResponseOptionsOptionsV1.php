<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesResponseOptionsOptionsV1 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $ageCheck;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $cashOnDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $collect;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $dropOffAtPostalPoint;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $extraAssurance;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $freshFood;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $frozen;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $hideSender;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $largeFormat;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $noTracking;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $onlyRecipient;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $printerlessReturn;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $priorityDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $receiptCode;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $return;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $sameDayDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $saturdayDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $signature;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV1
     */
    public RefCapabilitiesResponseOptionsOptionV1 $tracked;
    /**
     * @var RefCapabilitiesResponseOptionsInsuranceOptionV1
     */
    public RefCapabilitiesResponseOptionsInsuranceOptionV1 $insurance;
    public function definedProperties(): array
    {
        return ['ageCheck' => 'age_check', 'cashOnDelivery' => 'cash_on_delivery', 'collect' => 'collect', 'dropOffAtPostalPoint' => 'drop_off_at_postal_point', 'extraAssurance' => 'extra_assurance', 'freshFood' => 'fresh_food', 'frozen' => 'frozen', 'hideSender' => 'hide_sender', 'largeFormat' => 'large_format', 'noTracking' => 'no_tracking', 'onlyRecipient' => 'only_recipient', 'printerlessReturn' => 'printerless_return', 'priorityDelivery' => 'priority_delivery', 'receiptCode' => 'receipt_code', 'return' => 'return', 'sameDayDelivery' => 'same_day_delivery', 'saturdayDelivery' => 'saturday_delivery', 'signature' => 'signature', 'tracked' => 'tracked', 'insurance' => 'insurance'];
    }
}