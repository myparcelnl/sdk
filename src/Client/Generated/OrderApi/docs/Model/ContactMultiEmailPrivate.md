# # ContactMultiEmailPrivate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**locale** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePostRequestInnerLocale**](AddNotePostRequestInnerLocale.md) |  | [optional]
**phone_number** | **string** | The phone number of the contact. | [optional]
**type** | **string** | Discriminator for private contacts. |
**name** | **string** | The name of the contact person. |
**email_addresses** | **string[]** | The email addresses of the contact. The first email address will be used as the primary email address. The remaining will be used as extra email addresses if the right permissions are present. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
