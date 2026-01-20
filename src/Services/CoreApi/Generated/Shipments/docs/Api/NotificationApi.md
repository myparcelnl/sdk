# MyParcel\CoreApi\Generated\Shipments\NotificationApi



All URIs are relative to https://api.myparcel.nl, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteNotificationGroups()**](NotificationApi.md#deleteNotificationGroups) | **DELETE** /notification_groups/{ids} | Delete notification groups |
| [**disableAllNotificationTemplatesByGroup()**](NotificationApi.md#disableAllNotificationTemplatesByGroup) | **PUT** /notification_groups/{notification_group_id}/notification_templates/disable | Disable all notification templates in a notification group |
| [**disableNotificationTemplate()**](NotificationApi.md#disableNotificationTemplate) | **PUT** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id}/disable | Disable notification template |
| [**enableAllNotificationTemplatesByGroup()**](NotificationApi.md#enableAllNotificationTemplatesByGroup) | **PUT** /notification_groups/{notification_group_id}/notification_templates/enable | Enable all notification templates in a notification group |
| [**enableNotificationTemplate()**](NotificationApi.md#enableNotificationTemplate) | **PUT** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id}/enable | Enable notification template |
| [**getNotificationGroups()**](NotificationApi.md#getNotificationGroups) | **GET** /notification_groups | Get notification groups |
| [**getNotificationTemplates()**](NotificationApi.md#getNotificationTemplates) | **GET** /notification_groups/{notification_group_id}/notification_templates | Get notification templates |
| [**postNotificationGroups()**](NotificationApi.md#postNotificationGroups) | **POST** /notification_groups | Create notification groups |
| [**putNotificationTemplate()**](NotificationApi.md#putNotificationTemplate) | **PUT** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id} | Update notification template |
| [**sendTestNotification()**](NotificationApi.md#sendTestNotification) | **POST** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id}/test | Send test notification |


## `deleteNotificationGroups()`

```php
deleteNotificationGroups($ids)
```

Delete notification groups

Delete notification groups to stop receiving notifications about shipment events.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$ids = 'ids_example'; // string | One or more notification group IDs. Separate multiple IDs using `;`.

try {
    $apiInstance->deleteNotificationGroups($ids);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->deleteNotificationGroups: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **ids** | **string**| One or more notification group IDs. Separate multiple IDs using &#x60;;&#x60;. | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `disableAllNotificationTemplatesByGroup()`

```php
disableAllNotificationTemplatesByGroup($notification_group_id)
```

Disable all notification templates in a notification group

Disable all notification templates associated with a specific notification group.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_group_id = 56; // int | The ID of the notification group.

try {
    $apiInstance->disableAllNotificationTemplatesByGroup($notification_group_id);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->disableAllNotificationTemplatesByGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_group_id** | **int**| The ID of the notification group. | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `disableNotificationTemplate()`

```php
disableNotificationTemplate($notification_group_id, $notification_template_id)
```

Disable notification template

Disable a specific notification template associated with a specific notification group.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_group_id = 56; // int | The ID of the notification group.
$notification_template_id = 56; // int | The ID of the notification template.

try {
    $apiInstance->disableNotificationTemplate($notification_group_id, $notification_template_id);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->disableNotificationTemplate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_group_id** | **int**| The ID of the notification group. | |
| **notification_template_id** | **int**| The ID of the notification template. | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `enableAllNotificationTemplatesByGroup()`

```php
enableAllNotificationTemplatesByGroup($notification_group_id)
```

Enable all notification templates in a notification group

Enable all notification templates associated with a specific notification group.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_group_id = 56; // int | The ID of the notification group.

try {
    $apiInstance->enableAllNotificationTemplatesByGroup($notification_group_id);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->enableAllNotificationTemplatesByGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_group_id** | **int**| The ID of the notification group. | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `enableNotificationTemplate()`

```php
enableNotificationTemplate($notification_group_id, $notification_template_id)
```

Enable notification template

Enable a specific notification template associated with a specific notification group.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_group_id = 56; // int | The ID of the notification group.
$notification_template_id = 56; // int | The ID of the notification template.

try {
    $apiInstance->enableNotificationTemplate($notification_group_id, $notification_template_id);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->enableNotificationTemplate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_group_id** | **int**| The ID of the notification group. | |
| **notification_template_id** | **int**| The ID of the notification template. | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getNotificationGroups()`

```php
getNotificationGroups($shop_id): \MyParcel\CoreApi\Generated\Shipments\Model\NotificationResponsesNotificationGroups
```

Get notification groups

Retrieve notification groups to see which groups are set up to receive notifications about shipment events.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shop_id = new \MyParcel\CoreApi\Generated\Shipments\Model\\MyParcel\CoreApi\Generated\Shipments\Model\CommonParametersIds(); // \MyParcel\CoreApi\Generated\Shipments\Model\CommonParametersIds

try {
    $result = $apiInstance->getNotificationGroups($shop_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->getNotificationGroups: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **shop_id** | [**\MyParcel\CoreApi\Generated\Shipments\Model\CommonParametersIds**](../Model/.md)|  | [optional] |

### Return type

[**\MyParcel\CoreApi\Generated\Shipments\Model\NotificationResponsesNotificationGroups**](../Model/NotificationResponsesNotificationGroups.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getNotificationTemplates()`

```php
getNotificationTemplates($notification_group_id): \MyParcel\CoreApi\Generated\Shipments\Model\NotificationResponsesNotificationTemplates
```

Get notification templates

Retrieve notification templates associated with a specific notification group.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_group_id = 56; // int | The ID of the notification group.

try {
    $result = $apiInstance->getNotificationTemplates($notification_group_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->getNotificationTemplates: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_group_id** | **int**| The ID of the notification group. | |

### Return type

[**\MyParcel\CoreApi\Generated\Shipments\Model\NotificationResponsesNotificationTemplates**](../Model/NotificationResponsesNotificationTemplates.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postNotificationGroups()`

```php
postNotificationGroups($notification_post_notification_group_request): \MyParcel\CoreApi\Generated\Shipments\Model\NotificationResponsesNotificationGroups
```

Create notification groups

Create notification groups to receive notifications about shipment events.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_post_notification_group_request = new \MyParcel\CoreApi\Generated\Shipments\Model\NotificationPostNotificationGroupRequest(); // \MyParcel\CoreApi\Generated\Shipments\Model\NotificationPostNotificationGroupRequest | Request body for creating notification groups.

try {
    $result = $apiInstance->postNotificationGroups($notification_post_notification_group_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->postNotificationGroups: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_post_notification_group_request** | [**\MyParcel\CoreApi\Generated\Shipments\Model\NotificationPostNotificationGroupRequest**](../Model/NotificationPostNotificationGroupRequest.md)| Request body for creating notification groups. | |

### Return type

[**\MyParcel\CoreApi\Generated\Shipments\Model\NotificationResponsesNotificationGroups**](../Model/NotificationResponsesNotificationGroups.md)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `putNotificationTemplate()`

```php
putNotificationTemplate($notification_group_id, $notification_template_id, $notification_put_notification_template_request)
```

Update notification template

Update a notification template associated with a specific notification group.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_group_id = 56; // int | The ID of the notification group.
$notification_template_id = 56; // int | The ID of the notification template.
$notification_put_notification_template_request = new \MyParcel\CoreApi\Generated\Shipments\Model\NotificationPutNotificationTemplateRequest(); // \MyParcel\CoreApi\Generated\Shipments\Model\NotificationPutNotificationTemplateRequest | Request body for updating a notification template.

try {
    $apiInstance->putNotificationTemplate($notification_group_id, $notification_template_id, $notification_put_notification_template_request);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->putNotificationTemplate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_group_id** | **int**| The ID of the notification group. | |
| **notification_template_id** | **int**| The ID of the notification template. | |
| **notification_put_notification_template_request** | [**\MyParcel\CoreApi\Generated\Shipments\Model\NotificationPutNotificationTemplateRequest**](../Model/NotificationPutNotificationTemplateRequest.md)| Request body for updating a notification template. | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `sendTestNotification()`

```php
sendTestNotification($notification_group_id, $notification_template_id)
```

Send test notification

Send a test notification using a specific notification template associated with a specific notification group. The test notification is sent to the email address of the logged-in user.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\NotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$notification_group_id = 56; // int | The ID of the notification group.
$notification_template_id = 56; // int | The ID of the notification template.

try {
    $apiInstance->sendTestNotification($notification_group_id, $notification_template_id);
} catch (Exception $e) {
    echo 'Exception when calling NotificationApi->sendTestNotification: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **notification_group_id** | **int**| The ID of the notification group. | |
| **notification_template_id** | **int**| The ID of the notification template. | |

### Return type

void (empty response body)

### Authorization

[bearer](../../README.md#bearer)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
