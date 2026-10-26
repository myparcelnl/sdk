<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $additionalInsurance;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $deliverAtPostalPoint;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $freshFood;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV2
     */
    public RefCapabilitiesSharedOptionsBaseOptionV2 $frozen;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $hideSender;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $noTracking;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $oversizedPackage;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $priorityDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $printReturnLabelAtDropOff;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $recipientOnlyDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $requiresAgeVerification;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $requiresCashOnDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $requiresReceiptCode;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $requiresSignature;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $returnOnFirstFailedDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $sameDayDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $saturdayDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $scheduledCollection;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV2 $tracked;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2 $insurance;
    public function definedProperties(): array
    {
        return ['additionalInsurance' => 'additionalInsurance', 'deliverAtPostalPoint' => 'deliverAtPostalPoint', 'freshFood' => 'freshFood', 'frozen' => 'frozen', 'hideSender' => 'hideSender', 'noTracking' => 'noTracking', 'oversizedPackage' => 'oversizedPackage', 'priorityDelivery' => 'priorityDelivery', 'printReturnLabelAtDropOff' => 'printReturnLabelAtDropOff', 'recipientOnlyDelivery' => 'recipientOnlyDelivery', 'requiresAgeVerification' => 'requiresAgeVerification', 'requiresCashOnDelivery' => 'requiresCashOnDelivery', 'requiresReceiptCode' => 'requiresReceiptCode', 'requiresSignature' => 'requiresSignature', 'returnOnFirstFailedDelivery' => 'returnOnFirstFailedDelivery', 'sameDayDelivery' => 'sameDayDelivery', 'saturdayDelivery' => 'saturdayDelivery', 'scheduledCollection' => 'scheduledCollection', 'tracked' => 'tracked', 'insurance' => 'insurance'];
    }
}