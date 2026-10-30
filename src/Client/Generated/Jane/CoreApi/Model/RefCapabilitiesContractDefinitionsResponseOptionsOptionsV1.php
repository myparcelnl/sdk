<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $ageCheck;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $cashOnDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $collect;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $dropOffAtPostalPoint;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $extraAssurance;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $freshFood;
    /**
     * @var RefCapabilitiesSharedOptionsBaseOptionV1
     */
    public RefCapabilitiesSharedOptionsBaseOptionV1 $frozen;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $hideSender;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $largeFormat;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $noTracking;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $onlyRecipient;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $printerlessReturn;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $priorityDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $receiptCode;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $return;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $sameDayDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $saturdayDelivery;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $signature;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionV1 $tracked;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1 $insurance;
    public function definedProperties(): array
    {
        return ['ageCheck' => 'age_check', 'cashOnDelivery' => 'cash_on_delivery', 'collect' => 'collect', 'dropOffAtPostalPoint' => 'drop_off_at_postal_point', 'extraAssurance' => 'extra_assurance', 'freshFood' => 'fresh_food', 'frozen' => 'frozen', 'hideSender' => 'hide_sender', 'largeFormat' => 'large_format', 'noTracking' => 'no_tracking', 'onlyRecipient' => 'only_recipient', 'printerlessReturn' => 'printerless_return', 'priorityDelivery' => 'priority_delivery', 'receiptCode' => 'receipt_code', 'return' => 'return', 'sameDayDelivery' => 'same_day_delivery', 'saturdayDelivery' => 'saturday_delivery', 'signature' => 'signature', 'tracked' => 'tracked', 'insurance' => 'insurance'];
    }
}