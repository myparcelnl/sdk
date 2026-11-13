<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class RefShipmentGeneralSettingsTracktrace implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $bcc;
    /**
     * @var null|string
     */
    public ?string $bccEmail;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $carrierEmailBasicNotification;
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
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $emailOnHandedToCourier;
    /**
     * @var string
     */
    public string $fromAddressCompany;
    /**
     * @var string
     */
    public string $fromAddressEmail;
    /**
     * @var string
     */
    public string $printerIdentifier;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $saveRecipientAddress;
    /**
     * @var RefTypesIntBoolean|int
     */
    public RefTypesIntBoolean|int $sendTrackTraceEmails;
    public function definedProperties(): array
    {
        return ['bcc' => 'bcc', 'bccEmail' => 'bcc_email', 'carrierEmailBasicNotification' => 'carrier_email_basic_notification', 'deliveryNotification' => 'delivery_notification', 'deliveryNotificationEmail' => 'delivery_notification_email', 'disableAutoDetectPickup' => 'disable_auto_detect_pickup', 'emailOnHandedToCourier' => 'email_on_handed_to_courier', 'fromAddressCompany' => 'from_address_company', 'fromAddressEmail' => 'from_address_email', 'printerIdentifier' => 'printer_identifier', 'saveRecipientAddress' => 'save_recipient_address', 'sendTrackTraceEmails' => 'send_track_trace_emails'];
    }
}