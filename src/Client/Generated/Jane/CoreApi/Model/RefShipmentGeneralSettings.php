<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefShipmentGeneralSettings
{
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $saveRecipientAddress;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $deliveryNotification;
    /**
     * @var list<string>
     */
    public array $deliveryNotificationEmail;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $disableAutoDetectPickup;
    /**
     * @var string
     */
    public string $printerIdentifier;
    /**
     * @var RefShipmentGeneralSettingsTracktrace
     */
    public RefShipmentGeneralSettingsTracktrace $tracktrace;
}