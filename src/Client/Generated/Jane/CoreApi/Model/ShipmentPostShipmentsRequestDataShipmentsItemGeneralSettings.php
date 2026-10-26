<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettings
{
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsSaveRecipientAddress|int
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsSaveRecipientAddress|int $saveRecipientAddress;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsDeliveryNotification|int
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsDeliveryNotification|int $deliveryNotification;
    /**
     * @var list<string>
     */
    public array $deliveryNotificationEmail;
    /**
     * @var ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int
     */
    public ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int $disableAutoDetectPickup;
    /**
     * @var string
     */
    public string $printerIdentifier;
}