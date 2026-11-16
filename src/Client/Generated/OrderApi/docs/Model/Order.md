# # Order

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | The unique identifier of the order. |
**ordered_at** | **\DateTime** | The date and time the order was placed. |
**price** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Money**](Money.md) | The total price of the order. |
**shop_id** | **string** | The unique identifier of the shop. |
**short_id** | **string** | The short ID of the order. |
**status** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderStatus**](OrderStatus.md) |  |
**assigned_user_id** | **string** | The user ID assigned to the order. | [optional]
**billing_details** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\BillingDetails**](BillingDetails.md) | The billing details of the order. | [optional]
**customer_reference** | **string** | The customer reference of the order. | [optional]
**external_references** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ExternalReferences**](ExternalReferences.md) |  | [optional]
**lines** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Line[]**](Line.md) | The lines of the order | [optional]
**notes** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Note[]**](Note.md) | The notes of the order. | [optional]
**packages** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PackageResponse[]**](PackageResponse.md) | The packages of the order. | [optional]
**rules** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderRules**](OrderRules.md) |  | [optional]
**shipping** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Shipping**](Shipping.md) | The shipping details of the order. This should be used as default information when creating a new shipment. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
