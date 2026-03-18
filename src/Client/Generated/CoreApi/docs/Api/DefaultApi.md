# MyParcelNL\Sdk\Client\Generated\CoreApi\DefaultApi

All URIs are relative to https://api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getIndex()**](DefaultApi.md#getIndex) | **GET** / |  |


## `getIndex()`

```php
getIndex($user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApi\Model\GetIndex200Response
```



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


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->getIndex($user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getIndex: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\GetIndex200Response**](../Model/GetIndex200Response.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
