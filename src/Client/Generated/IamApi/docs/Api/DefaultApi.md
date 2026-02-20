# MyParcelNL\Sdk\Client\Generated\IamApi\DefaultApi

All URIs are relative to https://iam.api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**whoamiGet()**](DefaultApi.md#whoamiGet) | **GET** /whoami | Find out who you are and what you can. |


## `whoamiGet()`

```php
whoamiGet(): \MyParcelNL\Sdk\Client\Generated\IamApi\Model\WhoamiGet200Response
```

Find out who you are and what you can.

Who am I?

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\IamApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\IamApi\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer (JWT) authorization: jwt
$config = MyParcelNL\Sdk\Client\Generated\IamApi\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\IamApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->whoamiGet();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->whoamiGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\MyParcelNL\Sdk\Client\Generated\IamApi\Model\WhoamiGet200Response**](../Model/WhoamiGet200Response.md)

### Authorization

[apiKey](../../README.md#apiKey), [jwt](../../README.md#jwt)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
