<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettings
{
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsSaveRecipientAddress|int
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsSaveRecipientAddress|int $saveRecipientAddress;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsDeliveryNotification|int
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsDeliveryNotification|int $deliveryNotification;
    /**
     * @var list<string>
     */
    public array $deliveryNotificationEmail;
    /**
     * @var ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int
     */
    public ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int $disableAutoDetectPickup;
    /**
     * @var string
     */
    public string $printerIdentifier;
}