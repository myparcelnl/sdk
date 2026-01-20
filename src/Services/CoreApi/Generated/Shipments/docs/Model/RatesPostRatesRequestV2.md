# # RatesPostRatesRequestV2

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**shop_id** | **int** | The ID of the shop for which the rates are requested. | [optional]
**recipient** | [**\MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesRecipient**](CapabilitiesRecipient.md) |  |
**sender** | [**\MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesSender**](CapabilitiesSender.md) |  | [optional]
**pickup** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentPickupV2**](RefShipmentPickupV2.md) |  | [optional]
**carrier** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesCarrierV2**](RefTypesCarrierV2.md) |  |
**package_type** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentPackageTypeV2**](RefShipmentPackageTypeV2.md) |  |
**physical_properties** | [**\MyParcel\CoreApi\Generated\Shipments\Model\CapabilitiesPhysicalProperties**](CapabilitiesPhysicalProperties.md) |  | [optional]
**options** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RatesPostRatesRequestV2Options**](RatesPostRatesRequestV2Options.md) |  | [optional]
**delivery_type** | **string** |  | [optional]
**direction** | **string** | The shipment direction for which the rates are requested. | [optional]
**collo** | **int** | The number of collo for which the rates are requested. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
