# # ShipmentPutShipmentsRequestV11DataShipmentsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** |  |
**account_id** | **int** |  | [optional]
**shop_id** | **int** |  | [optional]
**contract_id** | **int** |  | [optional]
**reference_identifier** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerReferenceIdentifier**](ShipmentPostShipmentsRequestV11DataShipmentsInnerReferenceIdentifier.md) |  | [optional]
**carrier** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**status** | **int** |  | [optional]
**delivered** | **int** |  | [optional]
**recipient** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPutShipmentsRequestV11DataShipmentsInnerRecipient**](ShipmentPutShipmentsRequestV11DataShipmentsInnerRecipient.md) |  | [optional]
**sender** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentSender**](RefShipmentSender.md) |  | [optional]
**options** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentShipmentOptions**](RefShipmentShipmentOptions.md) |  |
**customs_declaration** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentCustomsDeclaration**](RefShipmentCustomsDeclaration.md) |  | [optional]
**physical_properties** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties**](ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties.md) |  | [optional]
**pickup** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentPickup**](RefShipmentPickup.md) |  | [optional]
**drop_off_point** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint**](ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint.md) |  | [optional]
**note** | **string** |  | [optional]
**general_settings** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings**](ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings.md) |  | [optional]
**multi_collo_main_shipment_id** | **int** |  | [optional]
**secondary_shipments** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner[]**](ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner.md) |  | [optional]
**collection_contact** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPutShipmentsRequestV11DataShipmentsInnerCollectionContact**](ShipmentPutShipmentsRequestV11DataShipmentsInnerCollectionContact.md) |  | [optional]
**hidden** | **int** |  | [optional]
**shipped_items** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner[]**](ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
