# # ShipmentRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**account_id** | **int** |  | [optional]
**shop_id** | **int** |  | [optional]
**contract_id** | **int** |  | [optional]
**reference_identifier** | **int** |  | [optional]
**recipient** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient**](ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient.md) |  | [optional]
**sender** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentSender**](RefShipmentSender.md) |  | [optional]
**recipients** | **float[]** |  | [optional]
**physical_properties** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties**](ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties.md) |  | [optional]
**options** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentShipmentOptions**](RefShipmentShipmentOptions.md) |  |
**customs_declaration** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentCustomsDeclaration**](RefShipmentCustomsDeclaration.md) |  | [optional]
**carrier** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**pickup** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentPickup**](RefShipmentPickup.md) |  | [optional]
**drop_off_point** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint**](ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint.md) |  | [optional]
**note** | **string** |  | [optional]
**status** | **int** |  | [optional]
**delivered** | **int** |  | [optional]
**general_settings** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings**](ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings.md) |  | [optional]
**secondary_shipments** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\SecondaryShipmentRequest[]**](SecondaryShipmentRequest.md) |  | [optional]
**collection_contact** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContact**](ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContact.md) |  | [optional]
**hidden** | **int** |  | [optional]
**shipped_items** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner[]**](ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
