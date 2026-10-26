<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings
{
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsSaveRecipientAddress|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsSaveRecipientAddress|int $saveRecipientAddress;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDeliveryNotification|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDeliveryNotification|int $deliveryNotification;
    /**
     * @var list<string>
     */
    public array $deliveryNotificationEmail;
    /**
     * @var ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int
     */
    public ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int $disableAutoDetectPickup;
    /**
     * @var string
     */
    public string $printerIdentifier;
}