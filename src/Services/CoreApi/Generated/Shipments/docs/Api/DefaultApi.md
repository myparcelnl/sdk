# MyParcelNL\Sdk\CoreApi\Generated\Shipments\DefaultApi

All URIs are relative to https://api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getIndex()**](DefaultApi.md#getIndex) | **GET** / |  |


## `getIndex()`

```php
getIndex(): \MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\GetIndex200Response
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: apiKey
$config = MyParcelNL\Sdk\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\CoreApi\Generated\Shipments\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getIndex();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getIndex: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\MyParcelNL\Sdk\CoreApi\Generated\Shipments\Model\GetIndex200Response**](../Model/GetIndex200Response.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
