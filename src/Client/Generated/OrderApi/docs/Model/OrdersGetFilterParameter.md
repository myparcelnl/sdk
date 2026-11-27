# # OrdersGetFilterParameter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**assigned_user_id** | **string[]** | The assigned user IDs to filter orders by. | [optional]
**carrier** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Carrier[]**](Carrier.md) | The carriers to filter orders by. | [optional]
**has_assigned_user** | **string** | Whether the order has an assigned user or not. | [optional]
**has_notes** | **string** | Whether the order has notes or not. | [optional]
**order_id** | **string[]** | The order IDs to filter orders by. | [optional]
**ordered_at** | **string[]** | Range of the order&#39;s placed at datetime. | [optional]
**package_status_group** | **string[]** | The status of a package to filter orders by. | [optional]
**package_type** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PackageType[]**](PackageType.md) | The type of a package to filter orders by. | [optional]
**price** | **string[]** | Range of an order&#39;s price. | [optional]
**region_code** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\RegionCode[]**](RegionCode.md) | The country codes to filter orders by. | [optional]
**sales_channel_id** | **string[]** | The sales channel IDs to filter orders by. | [optional]
**shipment_delayed** | **string** | Whether the order has a package with a delayed shipment or not. | [optional]
**shipment_direction** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentDirection[]**](ShipmentDirection.md) | The direction of a package&#39;s shipment to filter orders by. | [optional]
**shipment_payment_status** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PaymentStatus[]**](PaymentStatus.md) | The payment status of a shipment label associated with a package to filter orders by. | [optional]
**shop_id** | **string[]** | The shop IDs to filter orders by. | [optional]
**status** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\OrderStatus[]**](OrderStatus.md) | The statuses to filter orders by. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
