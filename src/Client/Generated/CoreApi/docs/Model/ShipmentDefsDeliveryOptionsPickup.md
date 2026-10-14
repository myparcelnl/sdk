# ShipmentDefsDeliveryOptionsPickup

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**date** | **\DateTime** | Represents a date in ISO 8601 format:  &#x60;&#x60;&#x60; YYYY-MM-DD &#x60;&#x60;&#x60;  Where:   - &#x60;YYYY&#x60; represents a four-digit year, &#x60;0000&#x60; through &#x60;9999&#x60;   - &#x60;MM&#x60; represents a zero-padded month of the year, &#x60;01&#x60; through &#x60;12&#x60;   - &#x60;DD&#x60; represents a zero-padded day of that month, &#x60;01&#x60; through &#x60;31&#x60; | [optional]
**time** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsDeliveryOptionsPickupTime[]**](ShipmentDefsDeliveryOptionsPickupTime.md) |  | [optional]
**location** | **string** |  | [optional]
**street** | **string** |  | [optional]
**number** | **string** |  | [optional]
**number_suffix** | **string** |  | [optional]
**postal_code** | **string** |  | [optional]
**city** | **string** |  | [optional]
**cc** | **string** |  | [optional]
**start_time** | **string** | Represents a time in ISO 8601 format:  &#x60;&#x60;&#x60; hh:mm:ss.u &#x60;&#x60;&#x60;  Where:   - &#x60;hh&#x60; represents a zero-padded hour, &#x60;00&#x60; through &#x60;24&#x60;   - &#x60;mm&#x60; represents a zero-padded minute, &#x60;00&#x60; through &#x60;59&#x60;   - &#x60;ss&#x60; (optional) represents a zero-padded second, &#x60;00&#x60; through &#x60;60&#x60;     (where &#x60;60&#x60; is only used to denote an added leap second)   - &#x60;.u&#x60; (optional) represents a fraction of a second, &#x60;.0&#x60; through &#x60;.999999+&#x60; | [optional]
**price** | **int** |  | [optional]
**price_comment** | **string** |  | [optional]
**comment** | **string** |  | [optional]
**phone_number** | **string** |  | [optional]
**opening_hours** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsDeliveryOptionsOpeningHours**](ShipmentDefsDeliveryOptionsOpeningHours.md) |  | [optional]
**distance** | **string** |  | [optional]
**latitude** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude**](CommonDefsLatitude.md) |  | [optional]
**longitude** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude**](CommonDefsLongitude.md) |  | [optional]
**location_code** | **string** |  | [optional]
**retail_network_id** | **string** |  | [optional]
**holiday** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsDeliveryOptionsPickupHoliday**](ShipmentDefsDeliveryOptionsPickupHoliday.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
