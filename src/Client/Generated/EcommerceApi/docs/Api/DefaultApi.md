# MyParcelNL\Sdk\Client\Generated\EcommerceApi\DefaultApi

All URIs are relative to http://localhost, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**connectRefreshPost()**](DefaultApi.md#connectRefreshPost) | **POST** /connect/refresh | Obtain a fresh access token for an established connection |
| [**connectStartGet()**](DefaultApi.md#connectStartGet) | **GET** /connect/start | Start the connect flow in the merchant browser |
| [**connectTokenPost()**](DefaultApi.md#connectTokenPost) | **POST** /connect/token | Exchange a one-time code for an access token |
| [**webhookOrdersPost()**](DefaultApi.md#webhookOrdersPost) | **POST** /webhook/orders | Push orders from a sales channel plugin |


## `connectRefreshPost()`

```php
connectRefreshPost($dpop): \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectRefreshPost200Response
```

Obtain a fresh access token for an established connection

Issues a fresh access token for an established connection. No body, the DPoP proof is the credential.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$dpop = 'dpop_example'; // string

try {
    $result = $apiInstance->connectRefreshPost($dpop);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->connectRefreshPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **dpop** | **string**|  | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectRefreshPost200Response**](../Model/ConnectRefreshPost200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `connectStartGet()`

```php
connectStartGet($jkt, $config, $nonce, $scope)
```

Start the connect flow in the merchant browser

Opened in the merchant browser by the plugin to start the connect flow. Redirects to the identity provider login.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$jkt = 'jkt_example'; // string
$config = 'config_example'; // string
$nonce = 'nonce_example'; // string
$scope = 'scope_example'; // string

try {
    $apiInstance->connectStartGet($jkt, $config, $nonce, $scope);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->connectStartGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jkt** | **string**|  | |
| **config** | **string**|  | |
| **nonce** | **string**|  | |
| **scope** | **string**|  | |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `text/html`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `connectTokenPost()`

```php
connectTokenPost($dpop, $connect_token_post_request): \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost200Response
```

Exchange a one-time code for an access token

Redeems the one-time code from the connect flow for the first access token. The code is single-use.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$dpop = 'dpop_example'; // string
$connect_token_post_request = new \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPostRequest(); // \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPostRequest | The one-time code to redeem.

try {
    $result = $apiInstance->connectTokenPost($dpop, $connect_token_post_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->connectTokenPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **dpop** | **string**|  | |
| **connect_token_post_request** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPostRequest**](../Model/ConnectTokenPostRequest.md)| The one-time code to redeem. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost200Response**](../Model/ConnectTokenPost200Response.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `webhookOrdersPost()`

```php
webhookOrdersPost($order): \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\FixedWebhookOrderResult[]
```

Push orders from a sales channel plugin

Receives orders from a connected sales channel plugin. The plugin calls this endpoint whenever new orders are available in the shop.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$order = array(new \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Order()); // \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Order[] | The orders to import.

try {
    $result = $apiInstance->webhookOrdersPost($order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->webhookOrdersPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **order** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Order[]**](../Model/Order.md)| The orders to import. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\FixedWebhookOrderResult[]**](../Model/WebhookOrdersPost202ResponseInner.md)

### Authorization

[dpop](../../README.md#dpop)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
