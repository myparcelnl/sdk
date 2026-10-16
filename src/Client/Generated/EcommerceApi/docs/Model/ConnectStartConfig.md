# ConnectStartConfig

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**platform** | **string** | Defaults to the platform of this deployment. | [optional]
**shop_name** | **string** | Display name of the shop. |
**shop_url** | **string** | HTTPS base URL of the shop. The browser returns to &lt;shopUrl&gt;/myparcel-connect/callback. |
**version** | **string** | Semver version of the plugin. Defaults to 0.0.1. | [optional]
**command_endpoint** | **string** | Optional absolute HTTPS URL that receives every MyParcel command for this connection. A reconnect replaces it; without it the plugin gets no commands. Must be canonical (lowercase host, no default port, path starts with /, no user name or password); other forms are rejected. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
