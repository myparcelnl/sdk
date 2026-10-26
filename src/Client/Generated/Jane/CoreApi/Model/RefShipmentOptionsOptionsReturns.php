<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentOptionsOptionsReturns implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var float
     */
    public float $ageCheck;
    /**
     * @var float
     */
    public float $cooledDelivery;
    /**
     * @var float
     */
    public float $dropOffAtPostalPoint;
    /**
     * @var float
     */
    public float $extraAssurance;
    /**
     * @var float
     */
    public float $hideSender;
    /**
     * @var float
     */
    public float $largeFormat;
    /**
     * @var float
     */
    public float $onlyRecipient;
    /**
     * @var float
     */
    public float $receiptCode;
    /**
     * @var float
     */
    public float $return;
    /**
     * @var float
     */
    public float $sameDayDelivery;
    /**
     * @var float
     */
    public float $saturdayDelivery;
    /**
     * @var float
     */
    public float $signature;
    public function definedProperties(): array
    {
        return ['ageCheck' => 'age_check', 'cooledDelivery' => 'cooled_delivery', 'dropOffAtPostalPoint' => 'drop_off_at_postal_point', 'extraAssurance' => 'extra_assurance', 'hideSender' => 'hide_sender', 'largeFormat' => 'large_format', 'onlyRecipient' => 'only_recipient', 'receiptCode' => 'receipt_code', 'return' => 'return', 'sameDayDelivery' => 'same_day_delivery', 'saturdayDelivery' => 'saturday_delivery', 'signature' => 'signature'];
    }
}