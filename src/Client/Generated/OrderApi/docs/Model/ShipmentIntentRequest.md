# # ShipmentIntentRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**carrier** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CarrierToCreate**](CarrierToCreate.md) |  | [optional]
**customs_declaration** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CustomsDeclaration**](CustomsDeclaration.md) |  | [optional]
**direction** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentDirection**](ShipmentDirection.md) |  | [optional]
**disable_auto_detect_pickup** | **bool** | When set to true, this field disables the automatic detection and change of a recipient&#39;s address to a pickup location, particularly when the recipient&#39;s address is very close to or within the same building as a pickup point. | [optional]
**package_type** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PackageType**](PackageType.md) |  | [optional]
**recipient** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentRequestRecipient**](ShipmentRequestRecipient.md) |  | [optional]
**custom_contract_id** | **string** | The unique identifier of the contract. | [optional]
**delivery** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\DeliveryInput**](DeliveryInput.md) |  | [optional]
**options** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentOptionsInput**](ShipmentOptionsInput.md) |  | [optional]
**pickup** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Pickup**](Pickup.md) |  | [optional]
**sender** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentRequestSender**](ShipmentRequestSender.md) |  | [optional]
**save_recipient_address** | **bool** | When set to true, the recipient address is saved to the user&#39;s address book. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
