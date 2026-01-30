# # SecondaryShipmentResource

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Unique ID of a Shipment. |
**parent_id** | **int** | Unique ID of a Shipment. |
**account_id** | **int** |  |
**shop_id** | **int** |  |
**shipment_type** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentType**](RefShipmentType.md) |  |
**recipient** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesRecipient**](ShipmentDefsShipmentPropertiesRecipient.md) |  |
**sender** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentSender**](ShipmentDefsShipmentSender.md) |  |
**status** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentStatus**](ShipmentDefsShipmentStatus.md) |  |
**options** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesOptions**](ShipmentDefsShipmentPropertiesOptions.md) |  |
**general_settings** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentGeneralSettings**](RefShipmentGeneralSettings.md) |  |
**pickup** | [**Null**](Null.md) |  |
**customs_declaration** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentCustomsDeclaration**](RefShipmentCustomsDeclaration.md) |  |
**physical_properties** | [**Null**](Null.md) |  |
**reference_identifier** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentReferenceIdentifier**](RefShipmentReferenceIdentifier.md) |  |
**transaction_status** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentTransactionStatus**](RefShipmentTransactionStatus.md) |  |
**drop_off_point** | **mixed** |  |
**hidden** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesIntBoolean**](RefTypesIntBoolean.md) |  |
**price** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesMoney**](RefTypesMoney.md) |  |
**barcode** | **string** |  |
**region** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesRegion**](ShipmentDefsShipmentPropertiesRegion.md) |  |
**external_provider** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsExternalProviderPropertiesDisplayName**](ShipmentDefsExternalProviderPropertiesDisplayName.md) |  |
**external_provider_id** | **int** |  |
**payment_status** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPaymentStatus**](ShipmentDefsShipmentPaymentStatus.md) |  |
**carrier_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**platform_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\AccountDefsPlatformPropertiesId**](AccountDefsPlatformPropertiesId.md) |  |
**origin** | **string** |  |
**user_agent** | **string** |  |
**secondary_shipments** | [**Null**](Null.md) |  | [optional]
**collection_contact** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\AccountDefsContact**](AccountDefsContact.md) |  |
**multi_collo_main_shipment_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesMultiColloMainShipmentId**](ShipmentDefsShipmentPropertiesMultiColloMainShipmentId.md) |  |
**external_identifier** | **string** |  |
**delayed** | **bool** |  | [default to false]
**delivered** | **bool** |  | [default to false]
**amount** | **int** |  | [optional]
**currency** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\BillingDefsCurrency**](BillingDefsCurrency.md) |  | [optional]
**contract_id** | **int** |  |
**link_consumer_portal** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPropertiesLinkConsumerPortal**](ShipmentDefsShipmentPropertiesLinkConsumerPortal.md) |  | [optional]
**partner_tracktraces** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPartnerTracktracesInner[]**](ShipmentDefsShipmentPartnerTracktracesInner.md) |  | [optional]
**pickup_request_number** | **string** |  | [optional]
**order_shipment_identifier** | **string** |  | [optional]
**shipped_items** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\Null[]**](Null.md) |  | [optional]
**created** | **string** |  |
**modified** | **string** |  |
**created_by** | **int** |  |
**modified_by** | **int** |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
