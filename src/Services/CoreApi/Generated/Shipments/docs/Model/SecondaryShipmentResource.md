# # SecondaryShipmentResource

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Unique ID of a Shipment. |
**parent_id** | **mixed** |  |
**account_id** | **int** |  |
**shop_id** | **int** |  |
**shipment_type** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentType**](RefShipmentType.md) |  |
**recipient** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesRecipient**](ShipmentDefsShipmentPropertiesRecipient.md) |  |
**sender** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentSender**](ShipmentDefsShipmentSender.md) |  |
**status** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentStatus**](ShipmentDefsShipmentStatus.md) |  |
**options** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesOptions**](ShipmentDefsShipmentPropertiesOptions.md) |  |
**general_settings** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentGeneralSettings**](RefShipmentGeneralSettings.md) |  |
**pickup** | [**Null**](Null.md) |  |
**customs_declaration** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesCustomsDeclaration**](ShipmentDefsShipmentPropertiesCustomsDeclaration.md) |  |
**physical_properties** | [**Null**](Null.md) |  |
**reference_identifier** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentReferenceIdentifier**](RefShipmentReferenceIdentifier.md) |  |
**transaction_status** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentTransactionStatus**](RefShipmentTransactionStatus.md) |  |
**drop_off_point** | **mixed** |  |
**hidden** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesIntBoolean**](RefTypesIntBoolean.md) |  |
**price** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesMoney**](RefTypesMoney.md) |  |
**barcode** | **string** |  |
**region** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesRegion**](ShipmentDefsShipmentPropertiesRegion.md) |  |
**external_provider** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesExternalProvider**](ShipmentDefsShipmentPropertiesExternalProvider.md) |  |
**external_provider_id** | **mixed** |  |
**payment_status** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPaymentStatus**](ShipmentDefsShipmentPaymentStatus.md) |  |
**carrier_id** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**platform_id** | [**\MyParcel\CoreApi\Generated\Shipments\Model\AccountDefsPlatformPropertiesId**](AccountDefsPlatformPropertiesId.md) |  |
**origin** | **mixed** |  |
**user_agent** | **string** |  |
**secondary_shipments** | **mixed[]** |  | [optional]
**collection_contact** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesCollectionContact**](ShipmentDefsShipmentPropertiesCollectionContact.md) |  |
**multi_collo_main_shipment_id** | **mixed** |  |
**external_identifier** | **string** |  |
**delayed** | **bool** |  | [default to false]
**delivered** | **bool** |  | [default to false]
**amount** | **int** |  | [optional]
**currency** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesCurrency**](ShipmentDefsShipmentPropertiesCurrency.md) |  | [optional]
**contract_id** | **int** |  |
**link_consumer_portal** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesLinkConsumerPortal**](ShipmentDefsShipmentPropertiesLinkConsumerPortal.md) |  | [optional]
**partner_tracktraces** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPartnerTracktracesInner[]**](ShipmentDefsShipmentPartnerTracktracesInner.md) |  | [optional]
**pickup_request_number** | **string** |  | [optional]
**order_shipment_identifier** | **string** |  | [optional]
**shipped_items** | [**\MyParcel\CoreApi\Generated\Shipments\Model\Null[]**](Null.md) |  | [optional]
**created** | **string** |  |
**modified** | **string** |  |
**created_by** | **mixed** |  |
**modified_by** | **mixed** |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
