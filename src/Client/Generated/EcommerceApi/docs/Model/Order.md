# # Order

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**ordered_at** | **\DateTime** | An ISO 8601 formatted datetime string with timezone offset. |
**source_id** | **string** | The order ID as defined in the source system. |
**billing_details** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\OrderBillingDetails**](OrderBillingDetails.md) |  | [optional]
**customer_reference** | **string** | The customer reference of the order. | [optional]
**lines** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\OrderLine[]**](OrderLine.md) |  | [optional]
**shipping** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\OrderShipping**](OrderShipping.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
