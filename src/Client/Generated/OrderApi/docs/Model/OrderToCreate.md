# OrderToCreate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**billing_details** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderToCreateBillingDetails**](OrderToCreateBillingDetails.md) |  | [optional]
**customer_reference** | **string** | The customer reference of the order. | [optional]
**lines** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderLineToCreate[]**](OrderLineToCreate.md) |  | [optional]
**ordered_at** | **\DateTime** | An ISO 8601 formatted datetime string with timezone offset. |
**shipping** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderToCreateShipping**](OrderToCreateShipping.md) |  | [optional]
**shop_id** | **string** | The unique identifier of the shop. |
**source_id** | **string** | The order ID as defined in the source system. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
