# # CommonResponsesProblem

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | URI reference identifying the problem type. Derived from the wrapping UserException constant name (e.g. &#x60;urn:problem:invalid-shipments&#x60;) or, when no matching constant exists, a status-based fallback URN. |
**title** | **string** | A short, human-readable summary of the problem type. |
**status** | **int** | The HTTP status code for this occurrence. |
**detail** | **string** | A human-readable explanation specific to this occurrence. |
**instance** | **string** | URI reference identifying the specific occurrence. The request path for the top-level object; the RFC 6901 JSON Pointer of the failing field for each &#x60;errors[]&#x60; entry. | [optional]
**request_id** | **string** | Per-request correlation identifier. Mirrors the value of the &#x60;X-Request-Id&#x60; response header and the legacy envelope&#39;s &#x60;request_id&#x60; field. Allowed as an RFC 9457 §3.2 extension member so consumers don&#39;t have to read both header and body for tracing. | [optional]
**errors** | [**\MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CommonResponsesProblemErrorsInner[]**](CommonResponsesProblemErrorsInner.md) | Sub-problem list. Each entry is itself an RFC 9457 problem-detail object describing one failing field. The sub-error &#x60;type&#x60;/&#x60;title&#x60; narrow past the wrapping problem when the underlying validator code maps to its own UserException constant (e.g. &#x60;urn:problem:invalid-address&#x60;, &#x60;urn:problem:invalid-physical-properties&#x60;). | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
