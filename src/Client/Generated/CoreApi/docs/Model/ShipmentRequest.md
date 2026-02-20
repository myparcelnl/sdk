# # ShipmentRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**account_id** | **int** |  | [optional]
**shop_id** | **int** |  | [optional]
**contract_id** | **int** |  | [optional]
**reference_identifier** | **int** |  | [optional]
**recipient** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient**](ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient.md) |  | [optional]
**sender** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentSender**](RefShipmentSender.md) |  | [optional]
**recipients** | **float[]** |  | [optional]
**physical_properties** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties**](ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties.md) |  | [optional]
**options** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentShipmentOptions**](RefShipmentShipmentOptions.md) |  |
**customs_declaration** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentCustomsDeclaration**](RefShipmentCustomsDeclaration.md) |  | [optional]
**carrier** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**pickup** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPickup**](RefShipmentPickup.md) |  | [optional]
**drop_off_point** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint**](ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint.md) |  | [optional]
**note** | **string** |  | [optional]
**status** | **int** |  | [optional]
**delivered** | **int** |  | [optional]
**general_settings** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings**](ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings.md) |  | [optional]
**secondary_shipments** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\SecondaryShipmentRequest[]**](SecondaryShipmentRequest.md) |  | [optional]
**collection_contact** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContact**](ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContact.md) |  | [optional]
**hidden** | **int** |  | [optional]
**shipped_items** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner[]**](ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
