# # PrincipalUser

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**account_id** | **string** | The unique identifier of the account. |
**mfa_enabled** | **bool** | Whether the account of the principal has MFA enabled. |
**platform** | [**\MyParcelNL\Sdk\Client\Generated\IamApi\Model\Platform**](Platform.md) |  |
**principal** | [**\MyParcelNL\Sdk\Client\Generated\IamApi\Model\PrincipalType**](PrincipalType.md) |  |
**features** | [**\MyParcelNL\Sdk\Client\Generated\IamApi\Model\Feature[]**](Feature.md) | A collection of features. | [optional]
**fulfilment_platforms** | [**\MyParcelNL\Sdk\Client\Generated\IamApi\Model\FulfilmentPlatform[]**](FulfilmentPlatform.md) | A collection of fulfilment platforms. | [optional]
**impersonator** | [**\MyParcelNL\Sdk\Client\Generated\IamApi\Model\PrincipalType**](PrincipalType.md) | The impersonator its principal ID. | [optional]
**id** | **string** | The unique identifier of the user. |
**role** | [**\MyParcelNL\Sdk\Client\Generated\IamApi\Model\RoleUser**](RoleUser.md) |  |
**shop_ids** | **string[]** | A collection of shop IDs that a user is allowed to access. |
**type** | **string** | The principal type. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
