# # ShipmentDefsShipment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Unique ID of a Shipment. |
**parent_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentParentId**](ShipmentDefsShipmentParentId.md) |  |
**account_id** | **int** |  |
**shop_id** | **int** |  |
**shipment_type** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentType**](RefShipmentType.md) |  |
**recipient** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentRecipient**](ShipmentDefsShipmentRecipient.md) |  |
**sender** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentSender**](ShipmentDefsShipmentSender.md) |  |
**status** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentStatus**](ShipmentDefsShipmentStatus.md) |  |
**options** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentOptions**](ShipmentDefsShipmentOptions.md) |  |
**general_settings** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentGeneralSettings**](RefShipmentGeneralSettings.md) |  |
**pickup** | [**Null**](Null.md) |  |
**customs_declaration** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentCustomsDeclaration**](ShipmentDefsShipmentCustomsDeclaration.md) |  |
**physical_properties** | [**Null**](Null.md) |  |
**reference_identifier** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentReferenceIdentifier**](RefShipmentReferenceIdentifier.md) |  |
**transaction_status** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefShipmentTransactionStatus**](RefShipmentTransactionStatus.md) |  |
**drop_off_point** | **mixed** |  |
**hidden** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesIntBoolean**](RefTypesIntBoolean.md) |  |
**price** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesMoney**](RefTypesMoney.md) |  |
**barcode** | **string** |  |
**region** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentRegion**](ShipmentDefsShipmentRegion.md) |  |
**external_provider** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentExternalProvider**](ShipmentDefsShipmentExternalProvider.md) |  |
**external_provider_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentExternalProviderId**](ShipmentDefsShipmentExternalProviderId.md) |  |
**payment_status** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPaymentStatus**](ShipmentDefsShipmentPaymentStatus.md) |  |
**carrier_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**platform_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\AccountDefsPlatformPropertiesId**](AccountDefsPlatformPropertiesId.md) |  |
**origin** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentOrigin**](ShipmentDefsShipmentOrigin.md) |  |
**user_agent** | **string** |  |
**secondary_shipments** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\SecondaryShipmentResource[]**](SecondaryShipmentResource.md) |  |
**collection_contact** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentCollectionContact**](ShipmentDefsShipmentCollectionContact.md) |  |
**multi_collo_main_shipment_id** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentParentId**](ShipmentDefsShipmentParentId.md) |  |
**external_identifier** | **string** |  |
**delayed** | **bool** |  | [default to false]
**delivered** | **bool** |  | [default to false]
**amount** | **int** |  | [optional]
**currency** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentCurrency**](ShipmentDefsShipmentCurrency.md) |  | [optional]
**contract_id** | **int** |  |
**link_consumer_portal** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentLinkConsumerPortal**](ShipmentDefsShipmentLinkConsumerPortal.md) |  | [optional]
**partner_tracktraces** | [**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\ShipmentDefsShipmentPartnerTracktracesInner[]**](ShipmentDefsShipmentPartnerTracktracesInner.md) |  | [optional]
**pickup_request_number** | [**Null**](Null.md) |  | [optional]
**order_shipment_identifier** | [**Null**](Null.md) |  | [optional]
**shipped_items** | [**Null**](Null.md) |  | [optional]
**created** | **string** |  |
**modified** | **string** |  |
**created_by** | **int** |  |
**modified_by** | **int** |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
