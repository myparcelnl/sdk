<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentOptionsOptionsReturns implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $ageCheck;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $cooledDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $dropOffAtPostalPoint;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $extraAssurance;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $hideSender;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $largeFormat;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $onlyRecipient;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $receiptCode;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $return;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $sameDayDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $saturdayDelivery;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $signature;
    public function definedProperties(): array
    {
        return ['ageCheck' => 'age_check', 'cooledDelivery' => 'cooled_delivery', 'dropOffAtPostalPoint' => 'drop_off_at_postal_point', 'extraAssurance' => 'extra_assurance', 'hideSender' => 'hide_sender', 'largeFormat' => 'large_format', 'onlyRecipient' => 'only_recipient', 'receiptCode' => 'receipt_code', 'return' => 'return', 'sameDayDelivery' => 'same_day_delivery', 'saturdayDelivery' => 'saturday_delivery', 'signature' => 'signature'];
    }
}