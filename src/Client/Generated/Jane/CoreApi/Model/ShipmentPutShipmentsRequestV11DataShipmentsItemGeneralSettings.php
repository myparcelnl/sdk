<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettings
{
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsSaveRecipientAddress|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsSaveRecipientAddress|int $saveRecipientAddress;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsDeliveryNotification|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsDeliveryNotification|int $deliveryNotification;
    /**
     * @var list<string>
     */
    public array $deliveryNotificationEmail;
    /**
     * @var ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int
     */
    public ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsDisableAutoDetectPickup|int $disableAutoDetectPickup;
    /**
     * @var string
     */
    public string $printerIdentifier;
}