<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefShipmentGeneralSettings
{
    /**
     * @var float
     */
    public float $saveRecipientAddress;
    /**
     * @var float
     */
    public float $deliveryNotification;
    /**
     * @var list<string>
     */
    public array $deliveryNotificationEmail;
    /**
     * @var float
     */
    public float $disableAutoDetectPickup;
    /**
     * @var string
     */
    public string $printerIdentifier;
    /**
     * @var RefShipmentGeneralSettingsTracktrace
     */
    public RefShipmentGeneralSettingsTracktrace $tracktrace;
}