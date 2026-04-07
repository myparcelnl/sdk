# MyParcelNL\Sdk\Client\Generated\CoreApi\ShipmentApi

All URIs are relative to https://api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteShipments()**](ShipmentApi.md#deleteShipments) | **DELETE** /shipments/{ids} | Delete Shipment |
| [**getDeliveryOptions()**](ShipmentApi.md#getDeliveryOptions) | **GET** /delivery_options | Get Delivery Options |
| [**getDropOffPoints()**](ShipmentApi.md#getDropOffPoints) | **GET** /drop_off_points | Get drop off points |
| [**getPickupLocations()**](ShipmentApi.md#getPickupLocations) | **GET** /pickup_locations | Get Pickup Locations |
| [**getShipments()**](ShipmentApi.md#getShipments) | **GET** /shipments | Gets a list of Shipments, optionally filtered using parameters. |
| [**getShipmentsById()**](ShipmentApi.md#getShipmentsById) | **GET** /shipments/{ids} | Get shipments by id. |
| [**getShipmentsLabels()**](ShipmentApi.md#getShipmentsLabels) | **GET** /shipment_labels/{ids} | Get Shipment labels |
| [**getTrackTraces()**](ShipmentApi.md#getTrackTraces) | **GET** /tracktraces | Track Shipment |
| [**getTrackTracesByIds()**](ShipmentApi.md#getTrackTracesByIds) | **GET** /tracktraces/{ids} | Track Shipment |
| [**postCapabilities()**](ShipmentApi.md#postCapabilities) | **POST** /shipments/capabilities | List shipment capabilities (Beta) |
| [**postCapabilitiesContractDefinitions()**](ShipmentApi.md#postCapabilitiesContractDefinitions) | **POST** /shipments/capabilities/contract-definitions | List a superset of available capabilities for the carriers and contracts associated with the logged-in user. (Beta) |
| [**postRates()**](ShipmentApi.md#postRates) | **POST** /shipments/rates | List shipment rates |
| [**postShipments()**](ShipmentApi.md#postShipments) | **POST** /shipments | Add Shipment |
| [**postUnrelatedReturnShipments()**](ShipmentApi.md#postUnrelatedReturnShipments) | **POST** /return_shipments | Generate unrelated return shipment URL |
| [**putShipment()**](ShipmentApi.md#putShipment) | **PUT** /shipments | Update Shipment |


## `deleteShipments()`

```php
deleteShipments($ids, $user_agent)
```

Delete Shipment

This operation can be used to delete shipments for which a label has not yet been created. However, outside of housekeeping, this is not really necessary since Shipments not handed over to the distributor will not be billed by MyParcel; thus unused shipments which are not deleted have no financial consequences.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$ids = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids | One or more shipment IDs. Separate multiple shipment IDs using `;`.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $apiInstance->deleteShipments($ids, $user_agent);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->deleteShipments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **ids** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**| One or more shipment IDs. Separate multiple shipment IDs using &#x60;;&#x60;. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |

### Return type

void (empty response body)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getDeliveryOptions()`

```php
getDeliveryOptions($cc, $postal_code, $number, $city, $street, $platform, $shop_id, $carrier, $delivery_date, $delivery_time, $cutoff_time, $dropoff_days, $monday_delivery, $dropoff_delay, $deliverydays_window, $exclude_delivery_type, $exclude_parcel_lockers, $latitude, $longitude): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDeliveryOptionsV2
```

Get Delivery Options

Get the delivery options for a given location and carrier.  If none of the optional parameters are specified then the following default will be used: If a request is made for the delivery options between Friday after the default cutoff_time (15:30) and Monday before the default cutoff_time (15:30) then Tuesday will be shown as the next possible delivery date.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$cc = 'cc_example'; // string | The country code for which to fetch the delivery options.
$postal_code = 'postal_code_example'; // string | The postal code for which to fetch the resources.
$number = 'number_example'; // string | The street number for which to fetch the resources.
$city = 'city_example'; // string | Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
$street = 'street_example'; // string | This can be used to narrow the search results for locations outside NL.
$platform = 'platform_example'; // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountParametersPlatformIdentifier | The platform where you want the data from.
$shop_id = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds
$carrier = 'carrier_example'; // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierIdentifier
$delivery_date = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime | The date on which the package has to be delivered.
$delivery_time = 'delivery_time_example'; // string | The time on which a package has to be delivered. > __Note__: This is only an indication of time the package will be >           delivered on the selected date.
$cutoff_time = 'cutoff_time_example'; // string | This option allows the **Merchant** to indicate the latest cutoff time before which a consumer order will still be picked, packed and dispatched on the same/first set drop-off day, taking into account the drop-off delay. Default time is 15:30. For example, if cutoff time is 15:30, Monday is a delivery day and there's no delivery delay; all orders placed Monday before 15:30 will be dropped of at PostNL on that same Monday in time for the Monday collection.
$dropoff_days = 'dropoff_days_example'; // string | This options allows the **Merchant** to set the days she normally goes to PostNL to hand in her parcels. By default Saturday and Sunday are excluded.
$monday_delivery = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean | Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. > __Note__: To activate Monday delivery, value 6 must be given with > [dropoff_days], value 1 must be given by [monday_delivery]. And on > Saturday the [cutoff_time] must be before 15:00 (14:30 recommended) > so that Monday will be shown.
$dropoff_delay = 56; // int | This options allows the **Merchant** to set the number of days it takes them to pick, pack and hand in their parcels at the carrier when ordered before the cutoff time. The default value is 0.
$deliverydays_window = 56; // int | This options allows the Merchant to set the number of days into the future for which they want to show their consumers delivery options. For example, if set to 3 in their check-out, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it's before the cutoff time, and they go to PostNL on Mondays).
$exclude_delivery_type = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersDeliveryType(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersDeliveryType | Exclude shipments with a specific delivery type. This parameter can be used multiple times to exclude multiple delivery types.
$exclude_parcel_lockers = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean | This option allows to filter out pickup locations that are parcel lockers.
$latitude = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude | This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
$longitude = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude | This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.

try {
    $result = $apiInstance->getDeliveryOptions($cc, $postal_code, $number, $city, $street, $platform, $shop_id, $carrier, $delivery_date, $delivery_time, $cutoff_time, $dropoff_days, $monday_delivery, $dropoff_delay, $deliverydays_window, $exclude_delivery_type, $exclude_parcel_lockers, $latitude, $longitude);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getDeliveryOptions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **cc** | **string**| The country code for which to fetch the delivery options. | [optional] |
| **postal_code** | **string**| The postal code for which to fetch the resources. | [optional] |
| **number** | **string**| The street number for which to fetch the resources. | [optional] |
| **city** | **string**| Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL. | [optional] |
| **street** | **string**| This can be used to narrow the search results for locations outside NL. | [optional] |
| **platform** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountParametersPlatformIdentifier**| The platform where you want the data from. | [optional] |
| **shop_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds**](../Model/.md)|  | [optional] |
| **carrier** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierIdentifier**|  | [optional] |
| **delivery_date** | **\DateTime**| The date on which the package has to be delivered. | [optional] |
| **delivery_time** | **string**| The time on which a package has to be delivered. &gt; __Note__: This is only an indication of time the package will be &gt;           delivered on the selected date. | [optional] |
| **cutoff_time** | **string**| This option allows the **Merchant** to indicate the latest cutoff time before which a consumer order will still be picked, packed and dispatched on the same/first set drop-off day, taking into account the drop-off delay. Default time is 15:30. For example, if cutoff time is 15:30, Monday is a delivery day and there&#39;s no delivery delay; all orders placed Monday before 15:30 will be dropped of at PostNL on that same Monday in time for the Monday collection. | [optional] |
| **dropoff_days** | **string**| This options allows the **Merchant** to set the days she normally goes to PostNL to hand in her parcels. By default Saturday and Sunday are excluded. | [optional] |
| **monday_delivery** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean**](../Model/.md)| Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. &gt; __Note__: To activate Monday delivery, value 6 must be given with &gt; [dropoff_days], value 1 must be given by [monday_delivery]. And on &gt; Saturday the [cutoff_time] must be before 15:00 (14:30 recommended) &gt; so that Monday will be shown. | [optional] |
| **dropoff_delay** | **int**| This options allows the **Merchant** to set the number of days it takes them to pick, pack and hand in their parcels at the carrier when ordered before the cutoff time. The default value is 0. | [optional] |
| **deliverydays_window** | **int**| This options allows the Merchant to set the number of days into the future for which they want to show their consumers delivery options. For example, if set to 3 in their check-out, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it&#39;s before the cutoff time, and they go to PostNL on Mondays). | [optional] |
| **exclude_delivery_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersDeliveryType**](../Model/.md)| Exclude shipments with a specific delivery type. This parameter can be used multiple times to exclude multiple delivery types. | [optional] |
| **exclude_parcel_lockers** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean**](../Model/.md)| This option allows to filter out pickup locations that are parcel lockers. | [optional] |
| **latitude** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude**| This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored. | [optional] |
| **longitude** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude**| This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDeliveryOptionsV2**](../Model/ShipmentResponsesDeliveryOptionsV2.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json;version=2.0`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getDropOffPoints()`

```php
getDropOffPoints($user_agent, $postal_code, $number, $distance, $cc, $limit, $carrier_id, $shop_id, $reference, $location_name, $external_identifier, $city, $cut_off_time, $min_cut_off_time, $max_cut_off_time, $latitude, $longitude, $exclude_parcel_lockers): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDropOffPoints
```

Get drop off points

Use this endpoint to receive a list of nearby drop off points, where shipments can be dropped off upon shipping. Results are ordered by distance from the provided postal code or coordinates.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$postal_code = 'postal_code_example'; // string | The postal code for which to fetch the resources.
$number = 'number_example'; // string | The street number for which to fetch the resources.
$distance = 56; // int | Provide the radius in kilometers for which you want to find drop off points. The default distance differs by carrier.
$cc = 'cc_example'; // string | The country code for which to fetch the delivery options.
$limit = 56; // int | Limit the number of resources returned.
$carrier_id = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierId(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierId
$shop_id = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds
$reference = 'reference_example'; // string | Filter by `reference`.
$location_name = 'location_name_example'; // string | Filter by location name.
$external_identifier = 'external_identifier_example'; // string
$city = 'city_example'; // string | Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
$cut_off_time = 'cut_off_time_example'; // string
$min_cut_off_time = 'min_cut_off_time_example'; // string
$max_cut_off_time = 'max_cut_off_time_example'; // string
$latitude = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude | This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
$longitude = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude | This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.
$exclude_parcel_lockers = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean | This option allows to filter out pickup locations that are parcel lockers.

try {
    $result = $apiInstance->getDropOffPoints($user_agent, $postal_code, $number, $distance, $cc, $limit, $carrier_id, $shop_id, $reference, $location_name, $external_identifier, $city, $cut_off_time, $min_cut_off_time, $max_cut_off_time, $latitude, $longitude, $exclude_parcel_lockers);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getDropOffPoints: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **postal_code** | **string**| The postal code for which to fetch the resources. | [optional] |
| **number** | **string**| The street number for which to fetch the resources. | [optional] |
| **distance** | **int**| Provide the radius in kilometers for which you want to find drop off points. The default distance differs by carrier. | [optional] |
| **cc** | **string**| The country code for which to fetch the delivery options. | [optional] |
| **limit** | **int**| Limit the number of resources returned. | [optional] |
| **carrier_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierId**](../Model/.md)|  | [optional] |
| **shop_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds**](../Model/.md)|  | [optional] |
| **reference** | **string**| Filter by &#x60;reference&#x60;. | [optional] |
| **location_name** | **string**| Filter by location name. | [optional] |
| **external_identifier** | **string**|  | [optional] |
| **city** | **string**| Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL. | [optional] |
| **cut_off_time** | **string**|  | [optional] |
| **min_cut_off_time** | **string**|  | [optional] |
| **max_cut_off_time** | **string**|  | [optional] |
| **latitude** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude**| This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored. | [optional] |
| **longitude** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude**| This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored. | [optional] |
| **exclude_parcel_lockers** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean**](../Model/.md)| This option allows to filter out pickup locations that are parcel lockers. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDropOffPoints**](../Model/ShipmentResponsesDropOffPoints.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPickupLocations()`

```php
getPickupLocations($cc, $postal_code, $number, $city, $street, $platform, $shop_id, $carrier, $delivery_date, $delivery_time, $cutoff_time, $dropoff_days, $monday_delivery, $dropoff_delay, $deliverydays_window, $exclude_delivery_type, $exclude_parcel_lockers, $latitude, $longitude): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPickupLocations
```

Get Pickup Locations

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$cc = 'cc_example'; // string | The country code for which to fetch the delivery options.
$postal_code = 'postal_code_example'; // string | The postal code for which to fetch the resources.
$number = 'number_example'; // string | The street number for which to fetch the resources.
$city = 'city_example'; // string | Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
$street = 'street_example'; // string | This can be used to narrow the search results for locations outside NL.
$platform = 'platform_example'; // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountParametersPlatformIdentifier | The platform where you want the data from.
$shop_id = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds
$carrier = 'carrier_example'; // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierIdentifier
$delivery_date = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime | The date on which the package has to be delivered.
$delivery_time = 'delivery_time_example'; // string | The time on which a package has to be delivered. > __Note__: This is only an indication of time the package will be >           delivered on the selected date.
$cutoff_time = 'cutoff_time_example'; // string | This option allows the **Merchant** to indicate the latest cutoff time before which a consumer order will still be picked, packed and dispatched on the same/first set drop-off day, taking into account the drop-off delay. Default time is 15:30. For example, if cutoff time is 15:30, Monday is a delivery day and there's no delivery delay; all orders placed Monday before 15:30 will be dropped of at PostNL on that same Monday in time for the Monday collection.
$dropoff_days = 'dropoff_days_example'; // string | This options allows the **Merchant** to set the days she normally goes to PostNL to hand in her parcels. By default Saturday and Sunday are excluded.
$monday_delivery = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean | Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. > __Note__: To activate Monday delivery, value 6 must be given with > [dropoff_days], value 1 must be given by [monday_delivery]. And on > Saturday the [cutoff_time] must be before 15:00 (14:30 recommended) > so that Monday will be shown.
$dropoff_delay = 56; // int | This options allows the **Merchant** to set the number of days it takes them to pick, pack and hand in their parcels at the carrier when ordered before the cutoff time. The default value is 0.
$deliverydays_window = 56; // int | This options allows the Merchant to set the number of days into the future for which they want to show their consumers delivery options. For example, if set to 3 in their check-out, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it's before the cutoff time, and they go to PostNL on Mondays).
$exclude_delivery_type = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersDeliveryType(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersDeliveryType | Exclude shipments with a specific delivery type. This parameter can be used multiple times to exclude multiple delivery types.
$exclude_parcel_lockers = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean | This option allows to filter out pickup locations that are parcel lockers.
$latitude = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude | This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
$longitude = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude | This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.

try {
    $result = $apiInstance->getPickupLocations($cc, $postal_code, $number, $city, $street, $platform, $shop_id, $carrier, $delivery_date, $delivery_time, $cutoff_time, $dropoff_days, $monday_delivery, $dropoff_delay, $deliverydays_window, $exclude_delivery_type, $exclude_parcel_lockers, $latitude, $longitude);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getPickupLocations: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **cc** | **string**| The country code for which to fetch the delivery options. | [optional] |
| **postal_code** | **string**| The postal code for which to fetch the resources. | [optional] |
| **number** | **string**| The street number for which to fetch the resources. | [optional] |
| **city** | **string**| Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL. | [optional] |
| **street** | **string**| This can be used to narrow the search results for locations outside NL. | [optional] |
| **platform** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\AccountParametersPlatformIdentifier**| The platform where you want the data from. | [optional] |
| **shop_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds**](../Model/.md)|  | [optional] |
| **carrier** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierIdentifier**|  | [optional] |
| **delivery_date** | **\DateTime**| The date on which the package has to be delivered. | [optional] |
| **delivery_time** | **string**| The time on which a package has to be delivered. &gt; __Note__: This is only an indication of time the package will be &gt;           delivered on the selected date. | [optional] |
| **cutoff_time** | **string**| This option allows the **Merchant** to indicate the latest cutoff time before which a consumer order will still be picked, packed and dispatched on the same/first set drop-off day, taking into account the drop-off delay. Default time is 15:30. For example, if cutoff time is 15:30, Monday is a delivery day and there&#39;s no delivery delay; all orders placed Monday before 15:30 will be dropped of at PostNL on that same Monday in time for the Monday collection. | [optional] |
| **dropoff_days** | **string**| This options allows the **Merchant** to set the days she normally goes to PostNL to hand in her parcels. By default Saturday and Sunday are excluded. | [optional] |
| **monday_delivery** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean**](../Model/.md)| Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. &gt; __Note__: To activate Monday delivery, value 6 must be given with &gt; [dropoff_days], value 1 must be given by [monday_delivery]. And on &gt; Saturday the [cutoff_time] must be before 15:00 (14:30 recommended) &gt; so that Monday will be shown. | [optional] |
| **dropoff_delay** | **int**| This options allows the **Merchant** to set the number of days it takes them to pick, pack and hand in their parcels at the carrier when ordered before the cutoff time. The default value is 0. | [optional] |
| **deliverydays_window** | **int**| This options allows the Merchant to set the number of days into the future for which they want to show their consumers delivery options. For example, if set to 3 in their check-out, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it&#39;s before the cutoff time, and they go to PostNL on Mondays). | [optional] |
| **exclude_delivery_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersDeliveryType**](../Model/.md)| Exclude shipments with a specific delivery type. This parameter can be used multiple times to exclude multiple delivery types. | [optional] |
| **exclude_parcel_lockers** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean**](../Model/.md)| This option allows to filter out pickup locations that are parcel lockers. | [optional] |
| **latitude** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLatitude**| This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored. | [optional] |
| **longitude** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonDefsLongitude**| This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPickupLocations**](../Model/ShipmentResponsesPickupLocations.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getShipments()`

```php
getShipments($user_agent, $barcode, $carrier_id, $created, $delayed, $delivered, $dropoff_today, $filter_hidden_shops, $hidden, $link_consumer_portal, $order, $package_type, $page, $q, $reference_identifier, $region, $shipment_type, $shop_id, $size, $sort, $status, $transaction_status): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipments
```

Gets a list of Shipments, optionally filtered using parameters.

This operation returns a list of Shipments available to this User.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$barcode = 'barcode_example'; // string
$carrier_id = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierId(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierId
$created = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime | When set, only resources created after this date will be returned. Inclusive.
$delayed = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean | Filter on whether the current event code means the shipment has been delayed.
$delivered = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersFilterValidateBool(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersFilterValidateBool
$dropoff_today = True; // bool | Use this parameter to only show Shipments that need to be dropped off today.
$filter_hidden_shops = True; // bool
$hidden = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersFilterValidateBool(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersFilterValidateBool
$link_consumer_portal = True; // bool
$order = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersOrder(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersOrder | Specify whether the results should be sorted in ascending or descending order.
$package_type = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersPackageType(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersPackageType | Filter by Package Type.
$page = 56; // int | Request a specific page of the results, used for paginated results.
$q = 'q_example'; // string | If this parameter is provided results will be filtered by the provided query or keyword.
$reference_identifier = 'reference_identifier_example'; // string | Filter by `reference_identifier`, an optional arbitrary identifier to identify the Shipment.
$region = 'region_example'; // string | The region, department, state or province of the address.
$shipment_type = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersShipmentType(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersShipmentType
$shop_id = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds
$size = 56; // int | Specify the number of resources returned per page, used for paginated results.
$sort = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersSortShipment(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersSortShipment | Sort Shipment results by a particular resource field.
$status = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersStatus(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersStatus | Filter by Shipment status. This filter will return only Shipments with the specified status.
$transaction_status = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentTransactionStatus(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentTransactionStatus

try {
    $result = $apiInstance->getShipments($user_agent, $barcode, $carrier_id, $created, $delayed, $delivered, $dropoff_today, $filter_hidden_shops, $hidden, $link_consumer_portal, $order, $package_type, $page, $q, $reference_identifier, $region, $shipment_type, $shop_id, $size, $sort, $status, $transaction_status);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getShipments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **barcode** | **string**|  | [optional] |
| **carrier_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierId**](../Model/.md)|  | [optional] |
| **created** | **\DateTime**| When set, only resources created after this date will be returned. Inclusive. | [optional] |
| **delayed** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBoolean**](../Model/.md)| Filter on whether the current event code means the shipment has been delayed. | [optional] |
| **delivered** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersFilterValidateBool**](../Model/.md)|  | [optional] |
| **dropoff_today** | **bool**| Use this parameter to only show Shipments that need to be dropped off today. | [optional] |
| **filter_hidden_shops** | **bool**|  | [optional] |
| **hidden** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersFilterValidateBool**](../Model/.md)|  | [optional] |
| **link_consumer_portal** | **bool**|  | [optional] |
| **order** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersOrder**](../Model/.md)| Specify whether the results should be sorted in ascending or descending order. | [optional] |
| **package_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersPackageType**](../Model/.md)| Filter by Package Type. | [optional] |
| **page** | **int**| Request a specific page of the results, used for paginated results. | [optional] |
| **q** | **string**| If this parameter is provided results will be filtered by the provided query or keyword. | [optional] |
| **reference_identifier** | **string**| Filter by &#x60;reference_identifier&#x60;, an optional arbitrary identifier to identify the Shipment. | [optional] |
| **region** | **string**| The region, department, state or province of the address. | [optional] |
| **shipment_type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersShipmentType**](../Model/.md)|  | [optional] |
| **shop_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersIds**](../Model/.md)|  | [optional] |
| **size** | **int**| Specify the number of resources returned per page, used for paginated results. | [optional] |
| **sort** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersSortShipment**](../Model/.md)| Sort Shipment results by a particular resource field. | [optional] |
| **status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersStatus**](../Model/.md)| Filter by Shipment status. This filter will return only Shipments with the specified status. | [optional] |
| **transaction_status** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentTransactionStatus**](../Model/.md)|  | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipments**](../Model/ShipmentResponsesShipments.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getShipmentsById()`

```php
getShipmentsById($ids, $user_agent, $link_consumer_portal): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipments
```

Get shipments by id.

Get shipments by id.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$ids = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids | One or more shipment IDs. Separate multiple shipment IDs using `;`.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$link_consumer_portal = True; // bool

try {
    $result = $apiInstance->getShipmentsById($ids, $user_agent, $link_consumer_portal);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getShipmentsById: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **ids** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**| One or more shipment IDs. Separate multiple shipment IDs using &#x60;;&#x60;. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **link_consumer_portal** | **bool**|  | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesShipments**](../Model/ShipmentResponsesShipments.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getShipmentsLabels()`

```php
getShipmentsLabels($ids, $user_agent, $format, $positions, $collect_date, $delivery_options_identifier)
```

Get Shipment labels

#### Get Shipment labels You can specify label format and positions of labels on the first page with the __format__ and __positions__ query parameters. The __positions__ query only works when you specify the A4 format and is only applied on the first page with labels. Accounts with __Post-payment__ payment methods can fetch multiple labels in one call. For accounts with __Pre-payment__ payment method an `HTTP 402 Payment Required` with a PaymentInstructions object is returned if the label has not been paid for yet.  ##### Multi-collo shipments When a label for a multi collo shipment is requested, labels for all shipments parts of the multi collo shipment will be generated. Each shipment within a multi collo shipment MUST be labeled with a specific label containing a unique barcode.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$ids = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids | One or more shipment IDs. Separate multiple shipment IDs using `;`.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$format = A4; // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersPaperSize | The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you can specify the label positions. When requesting the label for a shipment that contains a custom form, you can only request an A4 format.
$positions = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersLabelPosition(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersLabelPosition | The positions of the label on an A4 sheet. A position is identified by a digit from `1` to `4`. You can specify up to four positions separated by semicolons (`;`).  __Note__: This parameter only works when you specify the `A4` __format__ and only applies to the first page when requesting multiple pages worth of labels. Subsequent pages use the default positioning `1;2;3;4`.
$collect_date = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$delivery_options_identifier = 'delivery_options_identifier_example'; // string

try {
    $apiInstance->getShipmentsLabels($ids, $user_agent, $format, $positions, $collect_date, $delivery_options_identifier);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getShipmentsLabels: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **ids** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**| One or more shipment IDs. Separate multiple shipment IDs using &#x60;;&#x60;. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **format** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersPaperSize**](../Model/.md)| The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you can specify the label positions. When requesting the label for a shipment that contains a custom form, you can only request an A4 format. | [optional] |
| **positions** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersLabelPosition**](../Model/.md)| The positions of the label on an A4 sheet. A position is identified by a digit from &#x60;1&#x60; to &#x60;4&#x60;. You can specify up to four positions separated by semicolons (&#x60;;&#x60;).  __Note__: This parameter only works when you specify the &#x60;A4&#x60; __format__ and only applies to the first page when requesting multiple pages worth of labels. Subsequent pages use the default positioning &#x60;1;2;3;4&#x60;. | [optional] |
| **collect_date** | **\DateTime**|  | [optional] |
| **delivery_options_identifier** | **string**|  | [optional] |

### Return type

void (empty response body)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/*`, `application/json`, `application/vnd.shipment_label_link+json`, `application/vnd.shipment_label_link+json+print`, `application/json+print`, `application/pdf+print`, `application/vnd.shipment_label+json+print`, `application/vnd.shipment_label+pdf+print`, `application/vnd.shipment_label+zpl+print`, `application/vnd.zpl+print`, `*/*`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTrackTraces()`

```php
getTrackTraces($user_agent, $barcode, $country_code, $external_identifier, $extra_info, $postal_code, $sort): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces
```

Track Shipment

Get detailed Track & Trace information for one or more shipments.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$barcode = 'barcode_example'; // string
$country_code = 'country_code_example'; // string
$external_identifier = 'external_identifier_example'; // string
$extra_info = 'extra_info_example'; // string | Enables extra info in the response that is not included by default for performance reasons.
$postal_code = 'postal_code_example'; // string | The postal code for which to fetch the resources.
$sort = 'sort_example'; // string | Sort order. Defaults to `desc`.

try {
    $result = $apiInstance->getTrackTraces($user_agent, $barcode, $country_code, $external_identifier, $extra_info, $postal_code, $sort);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getTrackTraces: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **barcode** | **string**|  | [optional] |
| **country_code** | **string**|  | [optional] |
| **external_identifier** | **string**|  | [optional] |
| **extra_info** | **string**| Enables extra info in the response that is not included by default for performance reasons. | [optional] |
| **postal_code** | **string**| The postal code for which to fetch the resources. | [optional] |
| **sort** | **string**| Sort order. Defaults to &#x60;desc&#x60;. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces**](../Model/ShipmentResponsesTracktraces.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTrackTracesByIds()`

```php
getTrackTracesByIds($ids, $user_agent, $sort, $extra_info): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces
```

Track Shipment

Get detailed Track & Trace information for one or more shipments.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$ids = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids | One or more shipment IDs. Separate multiple shipment IDs using `;`.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$sort = 'sort_example'; // string | Sort order. Defaults to `desc`.
$extra_info = 'extra_info_example'; // string | Enables extra info in the response that is not included by default for performance reasons.

try {
    $result = $apiInstance->getTrackTracesByIds($ids, $user_agent, $sort, $extra_info);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->getTrackTracesByIds: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **ids** | **\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**| One or more shipment IDs. Separate multiple shipment IDs using &#x60;;&#x60;. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **sort** | **string**| Sort order. Defaults to &#x60;desc&#x60;. | [optional] |
| **extra_info** | **string**| Enables extra info in the response that is not included by default for performance reasons. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesTracktraces**](../Model/ShipmentResponsesTracktraces.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postCapabilities()`

```php
postCapabilities($user_agent, $capabilities_post_capabilities_request_v2): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2
```

List shipment capabilities (Beta)

**This endpoint is currently in beta and the API contract may change.** List shipment capabilities of the carriers for the MyParcel platforms. This endpoint allows you to determine what delivery options, package types, and shipment options are available for specific carriers and destinations.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$capabilities_post_capabilities_request_v2 = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2 | Request body for capabilities endpoint.

try {
    $result = $apiInstance->postCapabilities($user_agent, $capabilities_post_capabilities_request_v2);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->postCapabilities: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **capabilities_post_capabilities_request_v2** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2**](../Model/CapabilitiesPostCapabilitiesRequestV2.md)| Request body for capabilities endpoint. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2**](../Model/CapabilitiesResponsesCapabilitiesV2.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json;charset=utf-8;version=2.0`, `application/json;charset=utf-8`
- **Accept**: `application/json;charset=utf-8;version=2.0`, `application/json;charset=utf-8`, `application/*`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postCapabilitiesContractDefinitions()`

```php
postCapabilitiesContractDefinitions($user_agent, $capabilities_post_contract_definitions_request_v2): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2
```

List a superset of available capabilities for the carriers and contracts associated with the logged-in user. (Beta)

**This endpoint is currently in beta and the API contract may change.** List shipment capabilities of the carriers for the MyParcel platforms. This endpoint allows you to determine the complete set of delivery options, package types, and shipment options available to a specific account.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$capabilities_post_contract_definitions_request_v2 = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2 | Request body for capabilities contract definitions endpoint.

try {
    $result = $apiInstance->postCapabilitiesContractDefinitions($user_agent, $capabilities_post_contract_definitions_request_v2);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->postCapabilitiesContractDefinitions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **capabilities_post_contract_definitions_request_v2** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2**](../Model/CapabilitiesPostContractDefinitionsRequestV2.md)| Request body for capabilities contract definitions endpoint. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2**](../Model/CapabilitiesResponsesContractDefinitionsV2.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json;charset=utf-8;version=2.0`, `application/json;charset=utf-8`
- **Accept**: `application/json;charset=utf-8;version=2`, `application/json;charset=utf-8`, `application/*`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postRates()`

```php
postRates($user_agent, $rates_post_rates_request_v2): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefRatesResponseRateV2
```

List shipment rates

This endpoint allows you to determine the rates for a shipment configuration.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$rates_post_rates_request_v2 = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RatesPostRatesRequestV2(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RatesPostRatesRequestV2 | Request body for rates endpoint.

try {
    $result = $apiInstance->postRates($user_agent, $rates_post_rates_request_v2);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->postRates: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **rates_post_rates_request_v2** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RatesPostRatesRequestV2**](../Model/RatesPostRatesRequestV2.md)| Request body for rates endpoint. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefRatesResponseRateV2**](../Model/RefRatesResponseRateV2.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json;charset=utf-8;version=2.0`, `application/json;charset=utf-8`
- **Accept**: `application/json;charset=utf-8;version=2.0`, `application/json;charset=utf-8`, `application/*`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postShipments()`

```php
postShipments($user_agent, $shipment_post_shipments_request_v11, $format, $positions, $collect_date, $delivery_options_identifier): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPostShipmentsV12
```

Add Shipment

Add shipments allows you to create standard and related return shipments.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$shipment_post_shipments_request_v11 = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11 | Array of Shipment objects.
$format = A4; // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersPaperSize | The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you can specify the label positions. When requesting the label for a shipment that contains a custom form, you can only request an A4 format.
$positions = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersLabelPosition(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersLabelPosition | The positions of the label on an A4 sheet. A position is identified by a digit from `1` to `4`. You can specify up to four positions separated by semicolons (`;`).  __Note__: This parameter only works when you specify the `A4` __format__ and only applies to the first page when requesting multiple pages worth of labels. Subsequent pages use the default positioning `1;2;3;4`.
$collect_date = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$delivery_options_identifier = 'delivery_options_identifier_example'; // string

try {
    $result = $apiInstance->postShipments($user_agent, $shipment_post_shipments_request_v11, $format, $positions, $collect_date, $delivery_options_identifier);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->postShipments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **shipment_post_shipments_request_v11** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPostShipmentsRequestV11**](../Model/ShipmentPostShipmentsRequestV11.md)| Array of Shipment objects. | |
| **format** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersPaperSize**](../Model/.md)| The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you can specify the label positions. When requesting the label for a shipment that contains a custom form, you can only request an A4 format. | [optional] |
| **positions** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersLabelPosition**](../Model/.md)| The positions of the label on an A4 sheet. A position is identified by a digit from &#x60;1&#x60; to &#x60;4&#x60;. You can specify up to four positions separated by semicolons (&#x60;;&#x60;).  __Note__: This parameter only works when you specify the &#x60;A4&#x60; __format__ and only applies to the first page when requesting multiple pages worth of labels. Subsequent pages use the default positioning &#x60;1;2;3;4&#x60;. | [optional] |
| **collect_date** | **\DateTime**|  | [optional] |
| **delivery_options_identifier** | **string**|  | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPostShipmentsV12**](../Model/ShipmentResponsesPostShipmentsV12.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/vnd.shipment+json;version=1.1`, `application/vnd.shipment+json`, `application/vnd.return_shipment+json`, `application/vnd.unrelated_return_shipment+json`
- **Accept**: `application/json;charset=utf-8;version=1.2`, `application/json`, `application/vnd.shipment_label+json`, `application/*`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postUnrelatedReturnShipments()`

```php
postUnrelatedReturnShipments($user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonResponsesDownloadUrl
```

Generate unrelated return shipment URL

This endpoint is often used by external parties to facilitate return shipments on a dedicated part of their website, mainly when offering reverse logistics e.g. repair services. It will allow the consumer to send packages to the merchant directly from the merchant's website.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->postUnrelatedReturnShipments($user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->postUnrelatedReturnShipments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonResponsesDownloadUrl**](../Model/CommonResponsesDownloadUrl.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `putShipment()`

```php
putShipment($user_agent, $shipment_put_shipments_request_v11): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPutShipmentsV12
```

Update Shipment

This operation can be used to update certain fields of a shipment. The fields that can be modified depend on the status of the shipment.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$shipment_put_shipments_request_v11 = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPutShipmentsRequestV11(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPutShipmentsRequestV11 | Array of Shipment objects.

try {
    $result = $apiInstance->putShipment($user_agent, $shipment_put_shipments_request_v11);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->putShipment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **shipment_put_shipments_request_v11** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentPutShipmentsRequestV11**](../Model/ShipmentPutShipmentsRequestV11.md)| Array of Shipment objects. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesPutShipmentsV12**](../Model/ShipmentResponsesPutShipmentsV12.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/vnd.shipment+json;version=1.1`, `application/vnd.shipment+json`
- **Accept**: `application/json;charset=utf-8;version=1.2`, `application/json`, `application/*`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
