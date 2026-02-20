# # Shipment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | The unique identifier of the shipment. |
**carrier** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Carrier**](Carrier.md) |  |
**created_at** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentCreatedAt**](ShipmentCreatedAt.md) |  |
**direction** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentDirection**](ShipmentDirection.md) |  |
**hidden** | **bool** | Whether the shipment is hidden. |
**package_type** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\PackageType**](PackageType.md) |  |
**recipient** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentRecipient**](ShipmentRecipient.md) |  |
**custom_contract_id** | **string** | The unique identifier of the contract. | [optional]
**customs_declaration** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\CustomsDeclarationResponse**](CustomsDeclarationResponse.md) |  | [optional]
**delivery** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Delivery**](Delivery.md) |  | [optional]
**drop_off** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\DropOff**](DropOff.md) |  | [optional]
**label** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentLabel**](ShipmentLabel.md) |  | [optional]
**multicollo** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentMulticollo**](ShipmentMulticollo.md) |  | [optional]
**options** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentOptions**](ShipmentOptions.md) |  | [optional]
**pickup** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Pickup**](Pickup.md) |  | [optional]
**price** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\Money**](Money.md) |  | [optional]
**sender** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentRequestSender**](ShipmentRequestSender.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
