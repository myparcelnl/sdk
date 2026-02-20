# # ShipmentDefsShipment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Unique ID of a Shipment. |
**parent_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentParentId**](ShipmentDefsShipmentParentId.md) |  |
**account_id** | **int** |  |
**shop_id** | **int** |  |
**shipment_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentType**](RefShipmentType.md) |  |
**recipient** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentRecipient**](ShipmentDefsShipmentRecipient.md) |  |
**sender** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentSender**](ShipmentDefsShipmentSender.md) |  |
**status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentStatus**](ShipmentDefsShipmentStatus.md) |  |
**options** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentOptions**](ShipmentDefsShipmentOptions.md) |  |
**general_settings** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentGeneralSettings**](RefShipmentGeneralSettings.md) |  |
**pickup** | [**Null**](Null.md) |  |
**customs_declaration** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentCustomsDeclaration**](ShipmentDefsShipmentCustomsDeclaration.md) |  |
**physical_properties** | [**Null**](Null.md) |  |
**reference_identifier** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentReferenceIdentifier**](RefShipmentReferenceIdentifier.md) |  |
**transaction_status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentTransactionStatus**](RefShipmentTransactionStatus.md) |  |
**drop_off_point** | **mixed** |  |
**hidden** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesIntBoolean**](RefTypesIntBoolean.md) |  |
**price** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesMoney**](RefTypesMoney.md) |  |
**barcode** | **string** |  |
**region** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentRegion**](ShipmentDefsShipmentRegion.md) |  |
**external_provider** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentExternalProvider**](ShipmentDefsShipmentExternalProvider.md) |  |
**external_provider_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentExternalProviderId**](ShipmentDefsShipmentExternalProviderId.md) |  |
**payment_status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentPaymentStatus**](ShipmentDefsShipmentPaymentStatus.md) |  |
**carrier_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**platform_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountDefsPlatformPropertiesId**](AccountDefsPlatformPropertiesId.md) |  |
**origin** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentOrigin**](ShipmentDefsShipmentOrigin.md) |  |
**user_agent** | **string** |  |
**secondary_shipments** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\SecondaryShipmentResource[]**](SecondaryShipmentResource.md) |  |
**collection_contact** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentCollectionContact**](ShipmentDefsShipmentCollectionContact.md) |  |
**multi_collo_main_shipment_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentParentId**](ShipmentDefsShipmentParentId.md) |  |
**external_identifier** | **string** |  |
**delayed** | **bool** |  | [default to false]
**delivered** | **bool** |  | [default to false]
**amount** | **int** |  | [optional]
**currency** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentCurrency**](ShipmentDefsShipmentCurrency.md) |  | [optional]
**contract_id** | **int** |  |
**link_consumer_portal** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentLinkConsumerPortal**](ShipmentDefsShipmentLinkConsumerPortal.md) |  | [optional]
**partner_tracktraces** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentPartnerTracktracesInner[]**](ShipmentDefsShipmentPartnerTracktracesInner.md) |  | [optional]
**pickup_request_number** | [**Null**](Null.md) |  | [optional]
**order_shipment_identifier** | [**Null**](Null.md) |  | [optional]
**shipped_items** | [**Null**](Null.md) |  | [optional]
**created** | **string** |  |
**modified** | **string** |  |
**created_by** | **int** |  |
**modified_by** | **int** |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
