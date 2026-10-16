# # Product

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** |  |
**source_id** | **string** | The product ID as defined in the source system. |
**track_inventory** | **bool** | Whether the shop tracks the stock of this product. |
**barcode** | **string** |  | [optional]
**children** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ProductChild[]**](ProductChild.md) | The parts of a bundle product, identified by their own source ID. | [optional]
**contains_dangerous_substances** | **bool** |  | [optional]
**country_code_of_origin** | **string** | ISO 3166-1 alpha-2 country code. | [optional]
**descriptions** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\OrderLineProductDescriptionsInner[]**](OrderLineProductDescriptionsInner.md) | The descriptions of the product. | [optional]
**ean** | **string** |  | [optional]
**has_unlimited_shelf_life** | **bool** |  | [optional]
**hs_code** | **string** | The HS code of the product. | [optional]
**physical_properties** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\PhysicalProperties**](PhysicalProperties.md) |  | [optional]
**purchase_price** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Money**](Money.md) |  | [optional]
**sales_price** | [**\MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\SalesPrice**](SalesPrice.md) |  | [optional]
**sku** | **string** |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
