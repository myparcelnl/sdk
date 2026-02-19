# MyParcelNL\Sdk\Client\Generated\CoreApi\ShipmentApi

All URIs are relative to https://api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteShipments()**](ShipmentApi.md#deleteShipments) | **DELETE** /shipments/{ids} | Delete Shipment |
| [**getShipments()**](ShipmentApi.md#getShipments) | **GET** /shipments | Gets a list of Shipments, optionally filtered using parameters. |
| [**getShipmentsById()**](ShipmentApi.md#getShipmentsById) | **GET** /shipments/{ids} | Get shipments by id. |
| [**getShipmentsLabels()**](ShipmentApi.md#getShipmentsLabels) | **GET** /shipment_labels/{ids} | Get Shipment labels |
| [**postCapabilities()**](ShipmentApi.md#postCapabilities) | **POST** /shipments/capabilities | List shipment capabilities |
| [**postCapabilitiesContractDefinitions()**](ShipmentApi.md#postCapabilitiesContractDefinitions) | **POST** /shipments/capabilities/contract-definitions | List a superset of available capabilities for the carriers and contracts associated with the logged-in user. |
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


// Configure HTTP basic authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

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
| **ids** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**](../Model/.md)| One or more shipment IDs. Separate multiple shipment IDs using &#x60;;&#x60;. | |
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


// Configure HTTP basic authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

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
$carrier_id = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier
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
| **carrier_id** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier**](../Model/.md)|  | [optional] |
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


// Configure HTTP basic authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

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
| **ids** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**](../Model/.md)| One or more shipment IDs. Separate multiple shipment IDs using &#x60;;&#x60;. | |
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


// Configure HTTP basic authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

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
| **ids** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**](../Model/.md)| One or more shipment IDs. Separate multiple shipment IDs using &#x60;;&#x60;. | |
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

## `postCapabilities()`

```php
postCapabilities($capabilities_post_capabilities_request_v2): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2
```

List shipment capabilities

List shipment capabilities of the carriers for the MyParcel platforms. This endpoint allows you to determine what delivery options, package types, and shipment options are available for specific carriers and destinations.

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
$capabilities_post_capabilities_request_v2 = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2 | Request body for capabilities endpoint.

try {
    $result = $apiInstance->postCapabilities($capabilities_post_capabilities_request_v2);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->postCapabilities: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **capabilities_post_capabilities_request_v2** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2**](../Model/CapabilitiesPostCapabilitiesRequestV2.md)| Request body for capabilities endpoint. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2**](../Model/CapabilitiesResponsesCapabilitiesV2.md)

### Authorization

[bearer](../../README.md#bearer)

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

List a superset of available capabilities for the carriers and contracts associated with the logged-in user.

List shipment capabilities of the carriers for the MyParcel platforms. This endpoint allows you to determine the complete set of delivery options, package types, and shipment options available to a specific account.

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

[bearer](../../README.md#bearer)

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

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json;charset=utf-8;version=2.0`, `application/json;charset=utf-8`
- **Accept**: `application/json;charset=utf-8;version=2.0`, `application/json;charset=utf-8`, `application/*`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postShipments()`

```php
postShipments($user_agent, $shipment_post_shipments_request_v11, $format, $positions, $collect_date, $delivery_options_identifier): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\InlineObject
```

Add Shipment

Add shipments allows you to create standard and related return shipments.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

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

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\InlineObject**](../Model/InlineObject.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/vnd.shipment+json;version=1.1`, `application/vnd.shipment+json`, `application/vnd.return_shipment+json`, `application/vnd.unrelated_return_shipment+json`
- **Accept**: `application/json`, `application/vnd.shipment_label+json`, `application/*`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postUnrelatedReturnShipments()`

```php
postUnrelatedReturnShipments(): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonResponsesDownloadUrl
```

Generate unrelated return shipment URL

This endpoint is often used by external parties to facilitate return shipments on a dedicated part of their website, mainly when offering reverse logistics e.g. repair services. It will allow the consumer to send packages to the merchant directly from the merchant's website.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->postUnrelatedReturnShipments();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShipmentApi->postUnrelatedReturnShipments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonResponsesDownloadUrl**](../Model/CommonResponsesDownloadUrl.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `putShipment()`

```php
putShipment($user_agent, $shipment_put_shipments_request_v11)
```

Update Shipment

This operation can be used to update certain fields of a shipment. The fields that can be modified depend on the status of the shipment.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

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
    $apiInstance->putShipment($user_agent, $shipment_put_shipments_request_v11);
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

void (empty response body)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/vnd.shipment+json;version=1.1`, `application/vnd.shipment+json`
- **Accept**: `application/*`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
