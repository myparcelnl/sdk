<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesSharedOptionsBaseOptionsV1 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $ageCheck;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $cashOnDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $collect;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $dropOffAtPostalPoint;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $extraAssurance;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $freshFood;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $frozen;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $hideSender;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $largeFormat;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $noTracking;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $onlyRecipient;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $printerlessReturn;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $priorityDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $receiptCode;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $return;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $sameDayDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $saturdayDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $signature;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $tracked;
    public function definedProperties(): array
    {
        return ['ageCheck' => 'age_check', 'cashOnDelivery' => 'cash_on_delivery', 'collect' => 'collect', 'dropOffAtPostalPoint' => 'drop_off_at_postal_point', 'extraAssurance' => 'extra_assurance', 'freshFood' => 'fresh_food', 'frozen' => 'frozen', 'hideSender' => 'hide_sender', 'largeFormat' => 'large_format', 'noTracking' => 'no_tracking', 'onlyRecipient' => 'only_recipient', 'printerlessReturn' => 'printerless_return', 'priorityDelivery' => 'priority_delivery', 'receiptCode' => 'receipt_code', 'return' => 'return', 'sameDayDelivery' => 'same_day_delivery', 'saturdayDelivery' => 'saturday_delivery', 'signature' => 'signature', 'tracked' => 'tracked'];
    }
}