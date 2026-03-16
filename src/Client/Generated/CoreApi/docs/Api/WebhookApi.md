# MyParcelNL\Sdk\Client\Generated\CoreApi\WebhookApi

All URIs are relative to https://api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getWebhookSubscriptions()**](WebhookApi.md#getWebhookSubscriptions) | **GET** /webhook_subscriptions | Get webhook subscriptions |
| [**getWebhookSubscriptionsById()**](WebhookApi.md#getWebhookSubscriptionsById) | **GET** /webhook_subscriptions/{ids} | Get webhook subscriptions by id. |


## `getWebhookSubscriptions()`

```php
getWebhookSubscriptions($user_agent, $hook): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11
```

Get webhook subscriptions

Retrieve active webhook subscriptions for this account. Returns an array of Subscription objects.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
$hook = 'hook_example'; // string | Filter by webhook event type.

try {
    $result = $apiInstance->getWebhookSubscriptions($user_agent, $hook);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->getWebhookSubscriptions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |
| **hook** | **string**| Filter by webhook event type. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11**](../Model/WebhooksResponsesWebhookSubscriptionsV11.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json;charset=utf-8;version=1.1`, `application/json;charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getWebhookSubscriptionsById()`

```php
getWebhookSubscriptionsById($ids, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11
```

Get webhook subscriptions by id.

Retrieve specific webhook subscriptions by id. You can specify multiple subscription ids by semicolon separating them on the URI. Returns an array of Subscription objects.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$ids = new \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids(); // \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids | One or more webhook subscription IDs. Separate multiple IDs using `;`.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->getWebhookSubscriptionsById($ids, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->getWebhookSubscriptionsById: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **ids** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonParametersBigids**](../Model/.md)| One or more webhook subscription IDs. Separate multiple IDs using &#x60;;&#x60;. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11**](../Model/WebhooksResponsesWebhookSubscriptionsV11.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json;charset=utf-8;version=1.1`, `application/json;charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
