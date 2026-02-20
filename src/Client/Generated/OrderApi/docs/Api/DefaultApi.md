# MyParcelNL\Sdk\Client\Generated\OrderApi\DefaultApi

All URIs are relative to https://order.api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**addNotePost()**](DefaultApi.md#addNotePost) | **POST** /add-note | Add a note to an order. |
| [**addPackagesPost()**](DefaultApi.md#addPackagesPost) | **POST** /add-packages | Add packages to orders |
| [**assignToUserPost()**](DefaultApi.md#assignToUserPost) | **POST** /assign-to-user |  |
| [**cancelPost()**](DefaultApi.md#cancelPost) | **POST** /cancel | Cancel orders. |
| [**createFromShippablePackagesPost()**](DefaultApi.md#createFromShippablePackagesPost) | **POST** /create-from-shippable-packages | Create an order from shippable packages. |
| [**editNotePost()**](DefaultApi.md#editNotePost) | **POST** /edit-note | Edit a note of an order. |
| [**importPost()**](DefaultApi.md#importPost) | **POST** /import | Import an order after it is discovered from a sales channel. |
| [**ordersGet()**](DefaultApi.md#ordersGet) | **GET** /orders | Query and/or filter orders. |
| [**preparePackagesForShipmentPost()**](DefaultApi.md#preparePackagesForShipmentPost) | **POST** /prepare-packages-for-shipment | Prepares packages for shipment |
| [**removeNotesPost()**](DefaultApi.md#removeNotesPost) | **POST** /remove-notes | Remove notes from an order. |
| [**unassignFromUserPost()**](DefaultApi.md#unassignFromUserPost) | **POST** /unassign-from-user |  |
| [**unpreparePackagesForShipmentPost()**](DefaultApi.md#unpreparePackagesForShipmentPost) | **POST** /unprepare-packages-for-shipment | Unprepares packages for shipment |


## `addNotePost()`

```php
addNotePost($add_note_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePost200ResponseInner[]
```

Add a note to an order.

Add an order note. Only possible if the order has `status=OPEN` and the maximum number of 100 notes is not reached.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$add_note_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePostRequestInner[] | Body for adding an order note.

try {
    $result = $apiInstance->addNotePost($add_note_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->addNotePost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **add_note_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePostRequestInner[]**](../Model/AddNotePostRequestInner.md)| Body for adding an order note. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePost200ResponseInner[]**](../Model/AddNotePost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `addPackagesPost()`

```php
addPackagesPost($add_packages): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePost200ResponseInner[]
```

Add packages to orders

Add packages to orders

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$add_packages = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddPackages()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddPackages[] | Body adding packages to orders

try {
    $result = $apiInstance->addPackagesPost($add_packages);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->addPackagesPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **add_packages** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddPackages[]**](../Model/AddPackages.md)| Body adding packages to orders | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePost200ResponseInner[]**](../Model/AddNotePost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `assignToUserPost()`

```php
assignToUserPost($assign_to_user_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```



Assign orders to an user. Only possible if the order has `status=OPEN`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$assign_to_user_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPostRequestInner[] | Body for assigning orders to an user.

try {
    $result = $apiInstance->assignToUserPost($assign_to_user_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->assignToUserPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **assign_to_user_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPostRequestInner[]**](../Model/AssignToUserPostRequestInner.md)| Body for assigning orders to an user. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cancelPost()`

```php
cancelPost($cancel_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```

Cancel orders.

Cancel orders. Only possible if the order has `status=OPEN`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$cancel_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner[] | Body for canceling orders.

try {
    $result = $apiInstance->cancelPost($cancel_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->cancelPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **cancel_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner[]**](../Model/CancelPostRequestInner.md)| Body for canceling orders. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `createFromShippablePackagesPost()`

```php
createFromShippablePackagesPost($create_order_from_shippable_packages): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CreateFromShippablePackagesPost200ResponseInner[]
```

Create an order from shippable packages.

Create an order with packages that contain shipment properties.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$create_order_from_shippable_packages = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CreateOrderFromShippablePackages()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CreateOrderFromShippablePackages[] | Body for creating an order with shippable packages.

try {
    $result = $apiInstance->createFromShippablePackagesPost($create_order_from_shippable_packages);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->createFromShippablePackagesPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **create_order_from_shippable_packages** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CreateOrderFromShippablePackages[]**](../Model/CreateOrderFromShippablePackages.md)| Body for creating an order with shippable packages. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CreateFromShippablePackagesPost200ResponseInner[]**](../Model/CreateFromShippablePackagesPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `editNotePost()`

```php
editNotePost($edit_note_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```

Edit a note of an order.

Edit order note. Only possible if the order has `status=OPEN`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$edit_note_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\EditNotePostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\EditNotePostRequestInner[] | Body for editing an order note.

try {
    $result = $apiInstance->editNotePost($edit_note_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->editNotePost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **edit_note_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\EditNotePostRequestInner[]**](../Model/EditNotePostRequestInner.md)| Body for editing an order note. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `importPost()`

```php
importPost($cancel_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```

Import an order after it is discovered from a sales channel.

Import a discovered order. Only possible if the order has `status=DISCOVERED`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$cancel_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner[] | Body for importing an order.

try {
    $result = $apiInstance->importPost($cancel_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->importPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **cancel_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner[]**](../Model/CancelPostRequestInner.md)| Body for importing an order. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `ordersGet()`

```php
ordersGet($aggregation, $filter, $limit, $page_token, $query, $sort): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrdersGet200Response
```

Query and/or filter orders.

Get orders.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$aggregation = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Aggregation()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Aggregation[] | Fields to summarize data for based on the query.
$filter = ["filter[assignedUserId]=me","filter[carrier]=POST_NL","filter[countryCode]=NL","filter[hasAssignedUser]=true","filter[hasNotes]=true","filter[orderId]=123e4567-e89b-12d3-a456-426614174000","filter[orderedAt]=gte 2024-02-04T00:00:00Z","filter[price]=lte 10","filter[salesChannelId]=123e4567-e89b-12d3-a456-426614174000","filter[shopId]=555","filter[status]=OPEN","filter[status]=OPEN&filter[status]=CANCELED","filter[countryCode]=NL&filter[status]=OPEN"]; // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrdersGetFilterParameter
$limit = 50; // int | The maximum number of orders to retrieve.
$page_token = 'page_token_example'; // string | The page token to retrieve the next page of orders.
$query = 'query_example'; // string | The following operators are supported: '+ signifies AND operation' '| signifies OR operation' '- negates a single token' '\" wraps a number of tokens to signify a phrase for searching' '* at the end of a term signifies a prefix query' '( and ) signify precedence' '~N after a word signifies edit distance (fuzziness)' '~N after a phrase signifies slop amount' To use one of these characters literally, escape it with a preceding backslash (\\).
$sort = ["sort[customerReference]=DESC","sort[orderedAt]=ASC","sort[price]=ASC","sort[sourceId]=DESC","sort[customerReference]=ASC&sort[orderedAt]=DESC&sort[price]=ASC&sort[status]=ASC&sort[sourceId]=DESC"]; // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrdersGetSortParameter | When no `query` is provided the default sort is by `orderedAt` descending. When a `query` is provided the default sort is by relevance.

try {
    $result = $apiInstance->ordersGet($aggregation, $filter, $limit, $page_token, $query, $sort);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->ordersGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **aggregation** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Aggregation[]**](../Model/\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Aggregation.md)| Fields to summarize data for based on the query. | [optional] |
| **filter** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrdersGetFilterParameter**](../Model/.md)|  | [optional] |
| **limit** | **int**| The maximum number of orders to retrieve. | [optional] [default to 50] |
| **page_token** | **string**| The page token to retrieve the next page of orders. | [optional] |
| **query** | **string**| The following operators are supported: &#39;+ signifies AND operation&#39; &#39;| signifies OR operation&#39; &#39;- negates a single token&#39; &#39;\&quot; wraps a number of tokens to signify a phrase for searching&#39; &#39;* at the end of a term signifies a prefix query&#39; &#39;( and ) signify precedence&#39; &#39;~N after a word signifies edit distance (fuzziness)&#39; &#39;~N after a phrase signifies slop amount&#39; To use one of these characters literally, escape it with a preceding backslash (\\). | [optional] |
| **sort** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrdersGetSortParameter**](../Model/.md)| When no &#x60;query&#x60; is provided the default sort is by &#x60;orderedAt&#x60; descending. When a &#x60;query&#x60; is provided the default sort is by relevance. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrdersGet200Response**](../Model/OrdersGet200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `preparePackagesForShipmentPost()`

```php
preparePackagesForShipmentPost($prepare_packages_for_shipment): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```

Prepares packages for shipment

Prepares packages for shipment

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$prepare_packages_for_shipment = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PreparePackagesForShipment()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PreparePackagesForShipment[] | Body preparing packages for shipment

try {
    $result = $apiInstance->preparePackagesForShipmentPost($prepare_packages_for_shipment);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->preparePackagesForShipmentPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **prepare_packages_for_shipment** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PreparePackagesForShipment[]**](../Model/PreparePackagesForShipment.md)| Body preparing packages for shipment | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `removeNotesPost()`

```php
removeNotesPost($remove_notes_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```

Remove notes from an order.

Remove order notes. Only possible if the order has `status=OPEN`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$remove_notes_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\RemoveNotesPostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\RemoveNotesPostRequestInner[] | Body for removing order notes.

try {
    $result = $apiInstance->removeNotesPost($remove_notes_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->removeNotesPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **remove_notes_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\RemoveNotesPostRequestInner[]**](../Model/RemoveNotesPostRequestInner.md)| Body for removing order notes. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `unassignFromUserPost()`

```php
unassignFromUserPost($cancel_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```



Unassign orders from an user. Only possible if the order has `status=OPEN`

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$cancel_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner[] | Body for unassigning orders from an user.

try {
    $result = $apiInstance->unassignFromUserPost($cancel_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->unassignFromUserPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **cancel_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CancelPostRequestInner[]**](../Model/CancelPostRequestInner.md)| Body for unassigning orders from an user. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `unpreparePackagesForShipmentPost()`

```php
unpreparePackagesForShipmentPost($unprepare_packages_for_shipment_post_request_inner): \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]
```

Unprepares packages for shipment

Unprepares packages for shipment. Only possible if the order has `status=OPEN`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$unprepare_packages_for_shipment_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\UnpreparePackagesForShipmentPostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\UnpreparePackagesForShipmentPostRequestInner[] | Body for unpreparing packages for shipment

try {
    $result = $apiInstance->unpreparePackagesForShipmentPost($unprepare_packages_for_shipment_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->unpreparePackagesForShipmentPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **unprepare_packages_for_shipment_post_request_inner** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\UnpreparePackagesForShipmentPostRequestInner[]**](../Model/UnpreparePackagesForShipmentPostRequestInner.md)| Body for unpreparing packages for shipment | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AssignToUserPost200ResponseInner[]**](../Model/AssignToUserPost200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
