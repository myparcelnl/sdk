# # CommonDefsDateTime

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**date** | **string** | Represents a date in ISO 8601 format and a time in ISO 8601 format, separated by a space, so:  &#x60;&#x60;&#x60; YYYY-MM-DD hh:mm:ss.u &#x60;&#x60;&#x60;  Where:   - &#x60;YYYY&#x60; represents a four-digit year, &#x60;0000&#x60; through &#x60;9999&#x60;   - &#x60;MM&#x60; represents a zero-padded month of the year, &#x60;01&#x60; through &#x60;12&#x60;   - &#x60;DD&#x60; represents a zero-padded day of that month, &#x60;01&#x60; through &#x60;31&#x60; and:   - &#x60;hh&#x60; represents a zero-padded hour, &#x60;00&#x60; through &#x60;24&#x60;   - &#x60;mm&#x60; represents a zero-padded minute, &#x60;00&#x60; through &#x60;59&#x60;   - &#x60;ss&#x60; (optional) represents a zero-padded second, &#x60;00&#x60; through &#x60;60&#x60;     (where &#x60;60&#x60; is only used to denote an added leap second)   - &#x60;.u&#x60; (optional) represents a fraction of a second, &#x60;.0&#x60; through &#x60;.999999+&#x60; |
**timezone** | **string** |  |
**timezone_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsTimeZoneType**](CommonDefsTimeZoneType.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
