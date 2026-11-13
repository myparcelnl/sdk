# # LineProduct

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name of the product. |
**country_code_of_origin** | [**StringMixed**](StringMixed.md) |  | [optional]
**ean** | **string** | The EAN of the product. | [optional]
**external_reference** | **string** | Reference to the product in an external product catalog, like the zonos catalog id used for constructing a customs declaration. | [optional]
**physical_properties** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PhysicalProperties**](PhysicalProperties.md) |  | [optional]
**hs_code** | **string** | The HS code of the product. | [optional]
**sku** | **string** | The SKU of the product. | [optional]
**id** | **string** | Identifier of the product. This is always undefined when the &#x60;status&#x60; is &#x60;DISCOVERED&#x60; or &#x60;IMPORT_REQUESTED&#x60;, otherwise it is always defined. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
