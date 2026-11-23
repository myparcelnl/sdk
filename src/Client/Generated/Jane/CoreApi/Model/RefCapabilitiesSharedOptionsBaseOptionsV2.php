<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesSharedOptionsBaseOptionsV2 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $additionalInsurance;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $deliverAtPostalPoint;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $freshFood;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $frozen;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $hideSender;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $noTracking;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $oversizedPackage;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $priorityDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $printReturnLabelAtDropOff;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $recipientOnlyDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $requiresAgeVerification;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $requiresCashOnDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $requiresReceiptCode;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $requiresSignature;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $returnOnFirstFailedDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $sameDayDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $saturdayDelivery;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $scheduledCollection;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $tracked;
    public function definedProperties(): array
    {
        return ['additionalInsurance' => 'additionalInsurance', 'deliverAtPostalPoint' => 'deliverAtPostalPoint', 'freshFood' => 'freshFood', 'frozen' => 'frozen', 'hideSender' => 'hideSender', 'noTracking' => 'noTracking', 'oversizedPackage' => 'oversizedPackage', 'priorityDelivery' => 'priorityDelivery', 'printReturnLabelAtDropOff' => 'printReturnLabelAtDropOff', 'recipientOnlyDelivery' => 'recipientOnlyDelivery', 'requiresAgeVerification' => 'requiresAgeVerification', 'requiresCashOnDelivery' => 'requiresCashOnDelivery', 'requiresReceiptCode' => 'requiresReceiptCode', 'requiresSignature' => 'requiresSignature', 'returnOnFirstFailedDelivery' => 'returnOnFirstFailedDelivery', 'sameDayDelivery' => 'sameDayDelivery', 'saturdayDelivery' => 'saturdayDelivery', 'scheduledCollection' => 'scheduledCollection', 'tracked' => 'tracked'];
    }
}