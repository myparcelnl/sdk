# # SecondaryShipmentResource

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Unique ID of a Shipment. |
**parent_id** | **int** | Unique ID of a Shipment. |
**account_id** | **int** |  |
**shop_id** | **int** |  |
**shipment_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentType**](RefShipmentType.md) |  |
**recipient** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentPropertiesRecipient**](ShipmentDefsShipmentPropertiesRecipient.md) |  |
**sender** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\FixedShipmentSender**](ShipmentDefsShipmentSender.md) |  |
**status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentStatus**](ShipmentDefsShipmentStatus.md) |  |
**options** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentPropertiesOptions**](ShipmentDefsShipmentPropertiesOptions.md) |  |
**general_settings** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentGeneralSettings**](RefShipmentGeneralSettings.md) |  |
**pickup** | [**mixed**](Null.md) |  |
**customs_declaration** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentCustomsDeclaration**](RefShipmentCustomsDeclaration.md) |  |
**physical_properties** | [**mixed**](Null.md) |  |
**reference_identifier** | [**string**](RefShipmentReferenceIdentifier.md) |  |
**transaction_status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentTransactionStatus**](RefShipmentTransactionStatus.md) |  |
**drop_off_point** | **mixed** |  |
**hidden** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesIntBoolean**](RefTypesIntBoolean.md) |  |
**price** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesMoney**](RefTypesMoney.md) |  |
**barcode** | **string** |  |
**region** | [**string**](ShipmentDefsShipmentPropertiesRegion.md) |  |
**external_provider** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsExternalProviderPropertiesDisplayName**](ShipmentDefsExternalProviderPropertiesDisplayName.md) |  |
**external_provider_id** | **int** |  |
**payment_status** | [**string**](ShipmentDefsShipmentPaymentStatus.md) |  |
**carrier_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**platform_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountDefsPlatformId**](AccountDefsPlatformId.md) |  |
**origin** | **string** |  |
**user_agent** | **string** |  |
**secondary_shipments** | [**mixed**](Null.md) |  | [optional]
**collection_contact** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountDefsContact**](AccountDefsContact.md) |  |
**multi_collo_main_shipment_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentPropertiesMultiColloMainShipmentId**](ShipmentDefsShipmentPropertiesMultiColloMainShipmentId.md) |  |
**external_identifier** | **string** |  |
**delayed** | **bool** |  | [default to false]
**delivered** | **bool** |  | [default to false]
**amount** | **int** |  | [optional]
**currency** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\BillingDefsCurrency**](BillingDefsCurrency.md) |  | [optional]
**contract_id** | **int** |  |
**link_consumer_portal** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentPropertiesLinkConsumerPortal**](ShipmentDefsShipmentPropertiesLinkConsumerPortal.md) |  | [optional]
**partner_tracktraces** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsShipmentPartnerTracktracesInner[]**](ShipmentDefsShipmentPartnerTracktracesInner.md) |  | [optional]
**pickup_request_number** | **string** |  | [optional]
**order_shipment_identifier** | **string** |  | [optional]
**shipped_items** | [**mixed[]**](Null.md) |  | [optional]
**created** | **string** | Represents a date in ISO 8601 format and a time in ISO 8601 format, separated by a space, so:  &#x60;&#x60;&#x60; YYYY-MM-DD hh:mm:ss.u &#x60;&#x60;&#x60;  Where:   - &#x60;YYYY&#x60; represents a four-digit year, &#x60;0000&#x60; through &#x60;9999&#x60;   - &#x60;MM&#x60; represents a zero-padded month of the year, &#x60;01&#x60; through &#x60;12&#x60;   - &#x60;DD&#x60; represents a zero-padded day of that month, &#x60;01&#x60; through &#x60;31&#x60; and:   - &#x60;hh&#x60; represents a zero-padded hour, &#x60;00&#x60; through &#x60;24&#x60;   - &#x60;mm&#x60; represents a zero-padded minute, &#x60;00&#x60; through &#x60;59&#x60;   - &#x60;ss&#x60; (optional) represents a zero-padded second, &#x60;00&#x60; through &#x60;60&#x60;     (where &#x60;60&#x60; is only used to denote an added leap second)   - &#x60;.u&#x60; (optional) represents a fraction of a second, &#x60;.0&#x60; through &#x60;.999999+&#x60; |
**modified** | **string** | Represents a date in ISO 8601 format and a time in ISO 8601 format, separated by a space, so:  &#x60;&#x60;&#x60; YYYY-MM-DD hh:mm:ss.u &#x60;&#x60;&#x60;  Where:   - &#x60;YYYY&#x60; represents a four-digit year, &#x60;0000&#x60; through &#x60;9999&#x60;   - &#x60;MM&#x60; represents a zero-padded month of the year, &#x60;01&#x60; through &#x60;12&#x60;   - &#x60;DD&#x60; represents a zero-padded day of that month, &#x60;01&#x60; through &#x60;31&#x60; and:   - &#x60;hh&#x60; represents a zero-padded hour, &#x60;00&#x60; through &#x60;24&#x60;   - &#x60;mm&#x60; represents a zero-padded minute, &#x60;00&#x60; through &#x60;59&#x60;   - &#x60;ss&#x60; (optional) represents a zero-padded second, &#x60;00&#x60; through &#x60;60&#x60;     (where &#x60;60&#x60; is only used to denote an added leap second)   - &#x60;.u&#x60; (optional) represents a fraction of a second, &#x60;.0&#x60; through &#x60;.999999+&#x60; |
**created_by** | **int** |  |
**modified_by** | **int** |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
