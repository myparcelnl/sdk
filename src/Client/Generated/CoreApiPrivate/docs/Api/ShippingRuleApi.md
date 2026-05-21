# MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\ShippingRuleApi

All URIs are relative to https://api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteShippingRule()**](ShippingRuleApi.md#deleteShippingRule) | **DELETE** /shipping_rules/{shipping_rule_id} | Delete a shipping rule |
| [**getShippingRuleImplications()**](ShippingRuleApi.md#getShippingRuleImplications) | **GET** /shops/{shop_id}/shipping_rules/implications | Get shipping rule implications for a shop |
| [**getShippingRules()**](ShippingRuleApi.md#getShippingRules) | **GET** /shipping_rules | Get shipping rules |
| [**getShippingRulesByIds()**](ShippingRuleApi.md#getShippingRulesByIds) | **GET** /shipping_rules/{shipping_rule_ids} | Get shipping rules by id |
| [**getShippingRulesByShop()**](ShippingRuleApi.md#getShippingRulesByShop) | **GET** /shops/{shop_id}/shipping_rules | Get shipping rules for a shop |
| [**patchShippingRules()**](ShippingRuleApi.md#patchShippingRules) | **PATCH** /shipping_rules | Partially update shipping rules |
| [**postShippingRules()**](ShippingRuleApi.md#postShippingRules) | **POST** /shipping_rules | Create shipping rules |
| [**putShippingRules()**](ShippingRuleApi.md#putShippingRules) | **PUT** /shipping_rules | Replace shipping rules |


## `deleteShippingRule()`

```php
deleteShippingRule($shipping_rule_id, $user_agent)
```

Delete a shipping rule

Delete a single shipping rule by id.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shipping_rule_id = 56; // int | A single shipping rule ID.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $apiInstance->deleteShippingRule($shipping_rule_id, $user_agent);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->deleteShippingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shipping_rule_id** | **int**| A single shipping rule ID. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

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

## `getShippingRuleImplications()`

```php
getShippingRuleImplications($shop_id, $country, $region, $type, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesImplications
```

Get shipping rule implications for a shop

Resolve which implications (carrier, contract, shipment options, physical properties) apply to a shop given a criteria filter. Implications are derived from the shop's shipping rules with a fallback per region.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shop_id = 56; // int | The shop the shipping rules belong to.
$country = 'country_example'; // string | Filter implications by recipient country (ISO 3166-1 alpha-2, case-insensitive).
$region = new \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesRegion(); // \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesRegion | Filter by criteria region.
$type = new \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType(); // \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType | Filter by shipping rule type.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->getShippingRuleImplications($shop_id, $country, $region, $type, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->getShippingRuleImplications: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shop_id** | **int**| The shop the shipping rules belong to. | |
| **country** | **string**| Filter implications by recipient country (ISO 3166-1 alpha-2, case-insensitive). | [optional] |
| **region** | [**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesRegion**](../Model/.md)| Filter by criteria region. | [optional] |
| **type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType**](../Model/.md)| Filter by shipping rule type. | [optional] |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesImplications**](../Model/ShippingRulesResponsesImplications.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getShippingRules()`

```php
getShippingRules($page, $size, $shop_ids, $type, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated
```

Get shipping rules

Return the shipping rules visible to the authenticated account. When `shop_ids[]` is supplied, the result is filtered to those shops.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$page = 56; // int | Request a specific page of the results, used for paginated results.
$size = 50; // int | Number of shipping rules returned per page. Maximum 500.
$shop_ids = array(56); // int[] | Filter shipping rules by shop. Repeat the parameter for multiple shops.
$type = new \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType(); // \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType | Filter by shipping rule type.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->getShippingRules($page, $size, $shop_ids, $type, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->getShippingRules: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Request a specific page of the results, used for paginated results. | [optional] |
| **size** | **int**| Number of shipping rules returned per page. Maximum 500. | [optional] |
| **shop_ids** | [**int[]**](../Model/int.md)| Filter shipping rules by shop. Repeat the parameter for multiple shops. | [optional] |
| **type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType**](../Model/.md)| Filter by shipping rule type. | [optional] |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated**](../Model/ShippingRulesResponsesShippingRulesPaginated.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getShippingRulesByIds()`

```php
getShippingRulesByIds($shipping_rule_ids, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
```

Get shipping rules by id

Retrieve one or more shipping rules by their numeric IDs. Multiple IDs can be supplied by separating them with `;`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shipping_rule_ids = 'shipping_rule_ids_example'; // string | One or more shipping rule IDs. Separate multiple IDs using `;`.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->getShippingRulesByIds($shipping_rule_ids, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->getShippingRulesByIds: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shipping_rule_ids** | **string**| One or more shipping rule IDs. Separate multiple IDs using &#x60;;&#x60;. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules**](../Model/ShippingRulesResponsesShippingRules.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getShippingRulesByShop()`

```php
getShippingRulesByShop($shop_id, $page, $size, $type, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated
```

Get shipping rules for a shop

Return the shipping rules attached to a single shop.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shop_id = 56; // int | The shop the shipping rules belong to.
$page = 56; // int | Request a specific page of the results, used for paginated results.
$size = 50; // int | Number of shipping rules returned per page. Maximum 500.
$type = new \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType(); // \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType | Filter by shipping rule type.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->getShippingRulesByShop($shop_id, $page, $size, $type, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->getShippingRulesByShop: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shop_id** | **int**| The shop the shipping rules belong to. | |
| **page** | **int**| Request a specific page of the results, used for paginated results. | [optional] |
| **size** | **int**| Number of shipping rules returned per page. Maximum 500. | [optional] |
| **type** | [**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\RefShippingRulesType**](../Model/.md)| Filter by shipping rule type. | [optional] |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated**](../Model/ShippingRulesResponsesShippingRulesPaginated.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `patchShippingRules()`

```php
patchShippingRules($shipping_rules_patch_shipping_rules, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
```

Partially update shipping rules

Update one or more existing shipping rules in place.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shipping_rules_patch_shipping_rules = new \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPatchShippingRules(); // \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPatchShippingRules | Partial update of one or more shipping rules.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->patchShippingRules($shipping_rules_patch_shipping_rules, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->patchShippingRules: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shipping_rules_patch_shipping_rules** | [**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPatchShippingRules**](../Model/ShippingRulesPatchShippingRules.md)| Partial update of one or more shipping rules. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules**](../Model/ShippingRulesResponsesShippingRules.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postShippingRules()`

```php
postShippingRules($shipping_rules_post_shipping_rules, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
```

Create shipping rules

Create one or more shipping rules in a single request.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shipping_rules_post_shipping_rules = new \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPostShippingRules(); // \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPostShippingRules | Create one or more shipping rules.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->postShippingRules($shipping_rules_post_shipping_rules, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->postShippingRules: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shipping_rules_post_shipping_rules** | [**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPostShippingRules**](../Model/ShippingRulesPostShippingRules.md)| Create one or more shipping rules. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules**](../Model/ShippingRulesResponsesShippingRules.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `putShippingRules()`

```php
putShippingRules($shipping_rules_put_shipping_rules, $user_agent): \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules
```

Replace shipping rules

Replace one or more existing shipping rules.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shipping_rules_put_shipping_rules = new \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPutShippingRules(); // \MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPutShippingRules | Replace one or more shipping rules.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $result = $apiInstance->putShippingRules($shipping_rules_put_shipping_rules, $user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->putShippingRules: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shipping_rules_put_shipping_rules** | [**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesPutShippingRules**](../Model/ShippingRulesPutShippingRules.md)| Replace one or more shipping rules. | |
| **user_agent** | **string**| To give us insight into where requests come from and API documentation usage, you should send a &#x60;User-Agent&#x60; header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using. | [optional] |

### Return type

[**\MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules**](../Model/ShippingRulesResponsesShippingRules.md)

### Authorization

[apiKey](../../README.md#apiKey), [bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
