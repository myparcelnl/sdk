# # Order

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | The unique identifier of the order. |
**ordered_at** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderOrderedAt**](OrderOrderedAt.md) |  |
**price** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Money**](Money.md) |  |
**shop_id** | **string** | The unique identifier of the shop. |
**status** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderStatus**](OrderStatus.md) |  |
**assigned_user_id** | **string** | The user ID assigned to the order. | [optional]
**billing_details** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\BillingDetails**](BillingDetails.md) |  | [optional]
**customer_reference** | **string** | The customer reference of the order. | [optional]
**external_references** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ExternalReferences**](ExternalReferences.md) |  | [optional]
**lines** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Line[]**](Line.md) | The lines of the order | [optional]
**notes** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Note[]**](Note.md) | The notes of the order. | [optional]
**packages** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PackageResponse[]**](PackageResponse.md) | The packages of the order. | [optional]
**shipping** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Shipping**](Shipping.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
