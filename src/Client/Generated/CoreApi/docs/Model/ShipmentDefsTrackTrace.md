# # ShipmentDefsTrackTrace

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**shipment_id** | **int** | Unique ID of a Shipment. |
**carrier** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier**](RefTypesCarrier.md) |  |
**code** | **string** |  |
**description** | **string** |  |
**time** | **string** | Represents a date in ISO 8601 format and a time in ISO 8601 format, separated by a space, so:  &#x60;&#x60;&#x60; YYYY-MM-DD hh:mm:ss.u &#x60;&#x60;&#x60;  Where:   - &#x60;YYYY&#x60; represents a four-digit year, &#x60;0000&#x60; through &#x60;9999&#x60;   - &#x60;MM&#x60; represents a zero-padded month of the year, &#x60;01&#x60; through &#x60;12&#x60;   - &#x60;DD&#x60; represents a zero-padded day of that month, &#x60;01&#x60; through &#x60;31&#x60; and:   - &#x60;hh&#x60; represents a zero-padded hour, &#x60;00&#x60; through &#x60;24&#x60;   - &#x60;mm&#x60; represents a zero-padded minute, &#x60;00&#x60; through &#x60;59&#x60;   - &#x60;ss&#x60; (optional) represents a zero-padded second, &#x60;00&#x60; through &#x60;60&#x60;     (where &#x60;60&#x60; is only used to denote an added leap second)   - &#x60;.u&#x60; (optional) represents a fraction of a second, &#x60;.0&#x60; through &#x60;.999999+&#x60; |
**link_consumer_portal** | **string** |  |
**link_tracktrace** | **string** |  |
**partner_tracktraces** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsExternalTrackTraceLink[]**](ShipmentDefsExternalTrackTraceLink.md) |  |
**recipient** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\FixedShipmentRecipient**](ShipmentDefsShipmentRecipient.md) |  |
**sender** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\FixedShipmentSender**](ShipmentDefsShipmentSender.md) |  |
**options** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentShipmentOptions**](RefShipmentShipmentOptions.md) |  |
**pickup** | [**mixed**](Null.md) |  |
**delayed** | **bool** |  |
**location** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackingLocation**](ShipmentDefsTrackingLocation.md) |  |
**status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTraceStatus**](ShipmentDefsTrackTraceStatus.md) |  |
**history** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsCarrierTrackTraceEvent[]**](ShipmentDefsCarrierTrackTraceEvent.md) |  |
**delivery_moment_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsDeliveryMomentPropertiesType**](ShipmentDefsDeliveryMomentPropertiesType.md) |  | [optional]
**delivery_moment** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsDeliveryMomentPropertiesTimeFrame**](ShipmentDefsDeliveryMomentPropertiesTimeFrame.md) |  | [optional]
**signature** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsTrackTraceProofOfDelivery**](ShipmentDefsTrackTraceProofOfDelivery.md) |  | [optional]
**returnable** | **bool** |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
