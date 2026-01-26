# # ShipmentPutShipmentsRequestV11DataShipmentsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** |  |
**account_id** | **int** |  | [optional]
**shop_id** | **int** |  | [optional]
**contract_id** | **int** |  | [optional]
**reference_identifier** | **int** |  | [optional]
**carrier** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**status** | **int** |  | [optional]
**delivered** | **int** |  | [optional]
**recipient** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPutShipmentsRequestV11DataShipmentsInnerRecipient**](ShipmentPutShipmentsRequestV11DataShipmentsInnerRecipient.md) |  | [optional]
**sender** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentSender**](RefShipmentSender.md) |  | [optional]
**options** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentShipmentOptions**](RefShipmentShipmentOptions.md) |  |
**customs_declaration** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentCustomsDeclaration**](RefShipmentCustomsDeclaration.md) |  | [optional]
**physical_properties** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerPhysicalProperties**](ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerPhysicalProperties.md) |  | [optional]
**pickup** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentPickup**](RefShipmentPickup.md) |  | [optional]
**drop_off_point** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint**](ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint.md) |  | [optional]
**note** | **string** |  | [optional]
**general_settings** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings**](ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings.md) |  | [optional]
**multi_collo_main_shipment_id** | **int** |  | [optional]
**secondary_shipments** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner[]**](ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner.md) |  | [optional]
**collection_contact** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPutShipmentsRequestV11DataShipmentsInnerCollectionContact**](ShipmentPutShipmentsRequestV11DataShipmentsInnerCollectionContact.md) |  | [optional]
**hidden** | **int** |  | [optional]
**shipped_items** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner[]**](ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
