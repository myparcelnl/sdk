# # ShipmentDefsShipment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Unique ID of a Shipment. |
**parent_id** | **int** | Unique ID of a Shipment. |
**account_id** | **int** |  |
**shop_id** | **int** |  |
**shipment_type** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentType**](RefShipmentType.md) |  |
**recipient** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentRecipient**](ShipmentDefsShipmentRecipient.md) |  |
**sender** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentSender**](ShipmentDefsShipmentSender.md) |  |
**status** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentStatus**](ShipmentDefsShipmentStatus.md) |  |
**options** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentShipmentOptions**](RefShipmentShipmentOptions.md) |  |
**general_settings** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentGeneralSettings**](RefShipmentGeneralSettings.md) |  |
**pickup** | [**Null**](Null.md) |  |
**customs_declaration** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentCustomsDeclaration**](RefShipmentCustomsDeclaration.md) |  |
**physical_properties** | [**Null**](Null.md) |  |
**reference_identifier** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentReferenceIdentifier**](RefShipmentReferenceIdentifier.md) |  |
**transaction_status** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefShipmentTransactionStatus**](RefShipmentTransactionStatus.md) |  |
**drop_off_point** | **mixed** |  |
**hidden** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesIntBoolean**](RefTypesIntBoolean.md) |  |
**price** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesMoney**](RefTypesMoney.md) |  |
**barcode** | **string** |  |
**region** | **string** |  |
**external_provider** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsExternalProviderPropertiesDisplayName**](ShipmentDefsExternalProviderPropertiesDisplayName.md) |  |
**external_provider_id** | **int** |  |
**payment_status** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPaymentStatus**](ShipmentDefsShipmentPaymentStatus.md) |  |
**carrier_id** | [**\MyParcel\CoreApi\Generated\Shipments\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**platform_id** | [**\MyParcel\CoreApi\Generated\Shipments\Model\AccountDefsPlatformPropertiesId**](AccountDefsPlatformPropertiesId.md) |  |
**origin** | **string** |  |
**user_agent** | **string** |  |
**secondary_shipments** | [**\MyParcel\CoreApi\Generated\Shipments\Model\SecondaryShipmentResource[]**](SecondaryShipmentResource.md) |  |
**collection_contact** | [**\MyParcel\CoreApi\Generated\Shipments\Model\AccountDefsContact**](AccountDefsContact.md) |  |
**multi_collo_main_shipment_id** | **int** | Unique ID of a Shipment. |
**external_identifier** | **string** |  |
**delayed** | **bool** |  | [default to false]
**delivered** | **bool** |  | [default to false]
**amount** | **int** |  | [optional]
**currency** | [**\MyParcel\CoreApi\Generated\Shipments\Model\BillingDefsCurrency**](BillingDefsCurrency.md) |  | [optional]
**contract_id** | **int** |  |
**link_consumer_portal** | **string** |  | [optional]
**partner_tracktraces** | [**\MyParcel\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPartnerTracktracesInner[]**](ShipmentDefsShipmentPartnerTracktracesInner.md) |  | [optional]
**pickup_request_number** | **string** |  | [optional]
**order_shipment_identifier** | **string** |  | [optional]
**shipped_items** | [**\MyParcel\CoreApi\Generated\Shipments\Model\Null[]**](Null.md) |  | [optional]
**created** | **string** |  |
**modified** | **string** |  |
**created_by** | **mixed** |  |
**modified_by** | **mixed** |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
