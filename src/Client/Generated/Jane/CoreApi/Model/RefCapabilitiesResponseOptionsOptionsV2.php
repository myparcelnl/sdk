<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesResponseOptionsOptionsV2 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $additionalInsurance;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $deliverAtPostalPoint;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $freshFood;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $frozen;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $hideSender;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $noTracking;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $oversizedPackage;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $priorityDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $printReturnLabelAtDropOff;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $recipientOnlyDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $requiresAgeVerification;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $requiresCashOnDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $requiresReceiptCode;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $requiresSignature;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $returnOnFirstFailedDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $sameDayDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $saturdayDelivery;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $scheduledCollection;
    /**
     * @var RefCapabilitiesResponseOptionsOptionV2
     */
    public RefCapabilitiesResponseOptionsOptionV2 $tracked;
    /**
     * @var RefCapabilitiesResponseOptionsInsuranceOptionV2
     */
    public RefCapabilitiesResponseOptionsInsuranceOptionV2 $insurance;
    public function definedProperties(): array
    {
        return ['additionalInsurance' => 'additionalInsurance', 'deliverAtPostalPoint' => 'deliverAtPostalPoint', 'freshFood' => 'freshFood', 'frozen' => 'frozen', 'hideSender' => 'hideSender', 'noTracking' => 'noTracking', 'oversizedPackage' => 'oversizedPackage', 'priorityDelivery' => 'priorityDelivery', 'printReturnLabelAtDropOff' => 'printReturnLabelAtDropOff', 'recipientOnlyDelivery' => 'recipientOnlyDelivery', 'requiresAgeVerification' => 'requiresAgeVerification', 'requiresCashOnDelivery' => 'requiresCashOnDelivery', 'requiresReceiptCode' => 'requiresReceiptCode', 'requiresSignature' => 'requiresSignature', 'returnOnFirstFailedDelivery' => 'returnOnFirstFailedDelivery', 'sameDayDelivery' => 'sameDayDelivery', 'saturdayDelivery' => 'saturdayDelivery', 'scheduledCollection' => 'scheduledCollection', 'tracked' => 'tracked', 'insurance' => 'insurance'];
    }
}