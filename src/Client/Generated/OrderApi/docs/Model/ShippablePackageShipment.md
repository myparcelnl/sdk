# # ShippablePackageShipment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**customs_declaration** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CustomsDeclaration**](CustomsDeclaration.md) |  | [optional]
**options** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentOptions**](ShipmentOptions.md) |  | [optional]
**carrier** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CarrierToCreate**](CarrierToCreate.md) |  |
**direction** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentDirection**](ShipmentDirection.md) |  |
**disable_auto_detect_pickup** | **bool** | When set to true, this field disables the automatic detection and change of a recipient&#39;s address to a pickup location, particularly when the recipient&#39;s address is very close to or within the same building as a pickup point. |
**package_type** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PackageType**](PackageType.md) |  |
**recipient** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentRequestRecipient**](ShipmentRequestRecipient.md) |  |
**custom_contract_id** | **string** | The unique identifier of the contract. | [optional]
**delivery** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Delivery**](Delivery.md) |  | [optional]
**pickup** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Pickup**](Pickup.md) |  | [optional]
**sender** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentRequestSender**](ShipmentRequestSender.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
