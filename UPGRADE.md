# Upgrade instructions

## Upgrading to v12

v12 requires PHP 8.3 or higher, up from 7.4 in v11. Please ensure your hosting environment meets this requirement.
PHP 8.2 and lower are already no longer officially supported by PHP itself.

The PHP upgrade brings along some additional breaking changes to the SDK itself. Those will be documented below.

### The Jane clients

v12 generates the clients of the Core API, the private Core API, the IAM API and the Order API with [Jane](https://github.com/janephp/janephp) instead of OpenAPI Generator. The rules in this table hold for every service, factory and model on a Jane client. The sections after it name only what differs from these rules.

These services run on the Jane Core API client:

- `ShipmentQueryService`
- `ShipmentDeleteService`
- `ShipmentTrackTraceService`
- `ShipmentCreateService`
- `ShipmentPrintService`
- `ShipmentLabelsService`
- `ReturnShipmentService`
- `CapabilitiesService`
- `CarrierContractDefinitionsService`
- `WebhookService`

`ApiKeyService` runs on the Jane IAM client, and `CapabilitiesService` sends its requests through `HttpCapabilitiesClient`. The rows about the constructor, the User-Agent and the empty API key do not hold for these two services: see their rows in the Services table.

In this file, a class name that starts with `Jane\CoreApi` or `Jane\IamApi` is in `MyParcelNL\Sdk\Client\Generated\Jane`. A name that starts with `CoreApi` or `IamApi` is the v11 class in `MyParcelNL\Sdk\Client\Generated`, which v12 removes. `Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException` is a class of the Jane runtime, and `UnexpectedValueException` is the PHP class.

| Subject | v11 | v12 |
|---------|-----|-----|
| The client of a service or a factory | An OpenAPI Generator client: `CoreApi\Api\ShipmentApi`, `CoreApi\Api\WebhookApi` or `IamApi\Api\DefaultApi` | A `Jane\CoreApi\Client` or a `Jane\IamApi\Client`. `ShipmentApiFactory::make()` and `WebhookApiFactory::make()` return a `Jane\CoreApi\Client`, and `IamApiFactory::make()` returns a `Jane\IamApi\Client`. A service takes the API key, the client and the host, in this order: the client argument keeps its position, and only its type changes. The Services table names the classes that took other arguments |
| A model that a service returns | A model with getters and setters, such as `$shipment->getBarcode()` and `$shipment->getRecipient()->getPostalCode()` | The Jane model of the same schema, with public camelCase properties: `$shipment->barcode` and `$shipment->recipient->postalCode`. A Jane model has no setters, array access such as `$shipment['barcode']`, `__toString()`, `valid()` or `listInvalidProperties()` |
| A field that the API did not send | The getter returns `null` | The property is not initialized, and reading it throws an `Error`: read it with `??` or `isset()`, such as `$shipment->recipient->street ?? null`. A few properties have a default, such as `delayed`, which is `false` |
| A `null` from the API for a field that the spec does not allow as `null`, such as `barcode` | The getter returns `null` | The service call throws a PHP `TypeError` and returns no results. A `TypeError` is an `Error`, not an `Exception`, so `catch (\Exception $e)` does not catch it: catch `\Throwable` |
| An enum | A class with constants, such as `RefTypesCarrier::POSTNL`. A getter returns the raw value, such as `1` | A PHP enum: use the case, such as `RefTypesCarrier::Postnl`. A property holds the case, or the raw value for a value that the SDK does not know: compare with the case, or read `->value` |
| A flag of the shipment options, such as `signature`, and `hidden` of a found shipment | `0` or `1` | The enum case `RefTypesIntBoolean::True` or `RefTypesIntBoolean::False`. A property that you set also takes the int `1` or `0`. A case is an object, so `if ($shipment->hidden)` is always true: compare with the case, or read `->value`. `hidden` of a shipment that you create has its own enum: `ShipmentPostShipmentsRequestV11DataShipmentsItemHidden::Value1` or `Value0`, or the int |
| A model as JSON | `json_encode()` and `(string)` give the API field names | `json_encode()` gives the camelCase property names. `CoreApiModels::toArray($model)` gives an array with the API field names, such as `postal_code`: use `json_encode(CoreApiModels::toArray($model))`. It leaves out most fields that the API did not send or sent as `null`. A field with a default, such as `delayed`, is in the array |
| A request that does not match the spec | The API rejects it | The generated client checks the request before it sends it. It throws a `Jane\CoreApi\Runtime\Normalizer\ValidationException`, and no request goes out. `getViolationList()` lists each field and the problem. The API still checks the request too |
| A request body that cannot be encoded as JSON, such as invalid UTF-8 in a name | A `JsonException` | A Symfony `NotEncodableValueException`, and no request goes out |
| An error response of the Core API | A `CoreApi\ApiException`, with the HTTP status as `getCode()` | An exception that implements `Jane\CoreApi\Exception\ApiException`, such as `PostShipmentsClientErrorException`. Read the HTTP status with `getResponse()->getStatusCode()`, not with `getCode()`: `getCode()` is `0` for the exception of a status range, such as `PostShipmentsClientErrorException`, and the status for the exception of one status, such as `PostShipmentsUnprocessableEntityException`. Read the body with `getResponse()->getContent(false)` where you called `getResponseBody()` |
| The body of an error response of the Core API | Most requests send only `application/json` in `Accept`, and an error comes as `{"message": …, "request_id": …, "errors": […]}` | The generated endpoints send `application/json` and `application/problem+json` in `Accept`. The generated exception holds the model of the content type that the API answers with, in `getCommonResponsesProblem()`: a `Jane\CoreApi\Model\CommonResponsesUserError` with `->message` and `->statusCode` for `application/json`, or a `Jane\CoreApi\Model\CommonResponsesProblem` with `->detail` and `->status` for `application/problem+json`. Check which one you got with `instanceof`. The label requests of `ShipmentLabelsService` send only their own `Accept` value |
| A response with a status that the spec does not list for the request, or with a content type that is not JSON | No exception for a 2xx response | A `Jane\CoreApi\Exception\BadResponseException`, which is an `ApiException`. This includes a 2xx response, such as a 201 for `WebhookService::subscribe()` or a 200 for `unsubscribe()`, and an error page of a proxy, such as a 502 with HTML |
| A response with a JSON content type and a body that is not valid JSON | A `CoreApi\ApiException`, or a `JsonException` in `ReturnShipmentService` | A Symfony `NotEncodableValueException`, which is not an `ApiException`: catch it too |
| A connection error or a timeout | A `CoreApi\ApiException`. `ShipmentLabelsService`, `ShipmentPrintService` and `ReturnShipmentService` throw the PSR-18 `ClientExceptionInterface` of their HTTP client | A Symfony `TransportExceptionInterface` |
| The timeout | The client stops a request after 10 seconds in total | A Symfony HTTP client sends the requests, with an idle timeout of 10 seconds: a request stops when no data arrives for 10 seconds. A slow but steady download, such as a large label PDF, now completes. An HTTP client that you pass to a factory keeps its own timeout |
| The User-Agent of a Core API service | A service sends its User-Agent with every request, also with a client that you pass | A service sends its own User-Agent only with the client that it creates itself, with the value at the first request: call `setUserAgentForProposition()` before the first request. A client that you pass keeps its own User-Agent, and `setUserAgent()` and `setUserAgentForProposition()` on the service have no effect on it: give that client its User-Agent in the factory that creates it, such as `ShipmentApiFactory::make()` or `WebhookApiFactory::make()`. `ShipmentCreateService` and `ShipmentPrintService` also send the User-Agent of the service as `user_agent` in the request body, with every client |
| An empty API key in a Core API service | The constructor of a service throws an `InvalidArgumentException` | A service creates its client on the first request, and reads `API_KEY`, `API_KEY_NL` or `API_KEY_BE` then. So the first request throws the `InvalidArgumentException`, not the constructor |

### Services

This table names what differs from the rules above, per class.

| Class | v11 | v12 |
|-------|-----|-----|
| `ApiKeyService`        | `getPrincipal()` returns an `IamApi\Model\Principal` with getters, such as `getShopIds()` | `getPrincipal()` returns a `Jane\IamApi\Model\PrincipalShop` or `PrincipalUser` with public properties, such as `$principal->shopIds`. `type` is the enum case `PrincipalShopType::Shop` or `PrincipalUserType::User`. `role` and `platform` are enums, or the raw string for a value that the SDK does not know. `features` and `fulfilmentPlatforms` hold `Jane\IamApi\Model\Feature` and `FulfilmentPlatform` enum cases, or the raw string for a value that the SDK does not know: compare with `Feature::OrderManagement`, or read `->value`. They are optional: read them with `$principal->features ?? []`, because a Jane model does not initialize a field that IAM leaves out. `impersonator` is the string that IAM sends, or `null`, where `getImpersonator()` returned an empty `IamApi\Model\PrincipalType` |
| `ApiKeyService`        | `getPrincipal()` throws an `IamApi\ApiException` for every error, also for a network error. `isValid()` returns `false` for a 401 and throws the `ApiException` for every other error | `getPrincipal()` throws a `Jane\IamApi\Exception\ServerException` for a 500 with an `application/problem+json` body, a `Jane\IamApi\Exception\ClientException` for another error status or for a 200 without a JSON content type, a Symfony `TransportExceptionInterface` for a network error or a timeout, and a `Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException` for a 200 with a JSON content type and a body that is not valid JSON. `isValid()` returns `false` for a 401 and throws the others, also for a 200 without a JSON content type, where v11 returned `true` when the body was valid JSON. A 401 or a 500 with a JSON content type and a body that is not valid JSON throws a Symfony `NotEncodableValueException` in both methods. Every `Jane\IamApi` exception for an error response has the HTTP status as `getCode()`: read the body with `getResponse()->getContent(false)` where you called `getResponseBody()`. For a principal type that the SDK does not know, `getPrincipal()` throws an `UnexpectedValueException`, and `isValid()` returns `true`, because the 200 proves the key |
| `IamApiFactory`        | `make()` returns an `IamApi\Api\DefaultApi` | `make()` returns a `Jane\IamApi\Client`. Its `getWhoami()` returns a `Jane\IamApi\Model\PrincipalShop` or `PrincipalUser`, or the decoded JSON for a principal type that the SDK does not know |
| `ApiMapperService`     | `allRows()` and `allDomains()` key a row by the constant name of the openapi-generator enum, such as `DHL_FOR_YOU` | They key a row by the case name of the Jane enum, such as `DhlForYou`. The values of a row do not change |
| `ShipmentOptionMapper` | `v2PropertyFromName()` returns the property name of the openapi-generator model, such as `requires_signature` | It returns the property name of the Jane model, such as `requiresSignature`. A snake_case name, such as `requires_signature`, is still accepted as input |
| `ShipmentQueryService` | `query()` ignores a key it does not know, leaves out a filter with the value `false`, and sends an array as `status=1&status=2` | `query()` throws an `InvalidArgumentException` for a key it does not know, or for a type that the spec does not allow, such as `'hidden' => 1`: pass `true`. It sends `false` as `0`. `carrier_id`, `package_type`, `shipment_type`, `shop_id` and `status` take a list of integers, which goes as the `;`-separated string that the spec documents, such as `status=1;2`. An empty list leaves the filter out. Another array throws an `InvalidArgumentException`, such as a list of barcodes. For a list of shipment ids, use `findMany()` |
| `ShipmentPrintService`, `ShipmentLabelsService` and `ReturnShipmentService` | The third constructor argument is a PSR-18 HTTP client, and the fourth is the host | The third argument is the host. PHP ignores a fourth argument, so a v11 call with the host as the fourth argument sends to the default host without an error: move the host to the third argument. To use your own HTTP client, pass a client that was built with it |
| `ShipmentCreateService` and `ShipmentPrintService` | More than 100 shipments throw an `InvalidArgumentException`. `ShipmentPrintService` throws an `InvalidArgumentException` for an error response | More than 100 shipments throw the `ValidationException` of the generated client, as does a shipment without a recipient, carrier or options, options without `package_type`, or a recipient without `street` or `postal_code`. An error response throws the `ApiException` of the generated client |
| `MultiColloShipmentService` | `splitShipment()` takes and returns a `Shipment`. The secondary shipments are `CoreApi\Model\SecondaryShipmentRequest` objects. A shipment with an int reference identifier, such as `42`, gets a new `multi_collo_` reference identifier | It takes and returns a `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem`. The secondary shipments are `ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem` models. An int reference identifier is kept as the string `'42'` |
| `Order` (fulfilment) | `getDeliveryOptions()` returns a `ShipmentOptions`, and `setDeliveryOptions()` takes one | They return and take a `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions`. Build one with `new ...Options()`, and set `packageType` to `RefShipmentPackageType::Package` and `deliveryType` to `RefTypesDeliveryType::Standard->value`. For an order from the API with `"insurance": 500`, `getDeliveryOptions()->insurance` is a `RefTypesPriceEuro` with `amount` `50000`, where `getInsurance()` returned `500` in v11. A package type or delivery type that the order gives as a v2 name, such as `PACKAGE` or `STANDARD_DELIVERY`, becomes the id. For a name that the SDK does not know, the property is not set: read it with `??` |
| `ShipmentLabelsService` | `setLinkOfLabels()` and `setPdfOfLabels()` throw the SDK `MyParcelNL\Sdk\Exception\ApiException` for an error response, with the message `Did not receive expected label link response`, `Received payment link instead of pdf`, or `Did not receive expected pdf response` for another error of `setPdfOfLabels()` | They throw a generated `Jane\CoreApi\Exception\ApiException` for an error response, picked by the status and the content type: `GetShipmentsLabelsNotFoundException` for a 404, `GetShipmentsLabelsPaymentRequiredException` for a 402 with the content type `application/vnd.shipment_label_link+json`, and `GetShipmentsLabelsClientErrorException` or `GetShipmentsLabelsServerErrorException` for another error with the content type `application/json` or `application/problem+json`, such as a 402 with `application/json`. An error response with another content type, such as a 502 with an HTML page from a proxy, throws `BadResponseException`. A 2xx response without a link or a PDF still throws the SDK `ApiException`. In `setPdfOfLabels()`, a 2xx response with a content type outside `application/*` throws `BadResponseException` |
| `ReturnShipmentService` | It throws an `InvalidArgumentException` for an error response. A return without a field that the spec requires goes to the API | An error response throws the `ApiException` of the generated client, such as `PostShipmentsClientErrorException`. `createRelated()` throws the `ValidationException` of the generated client for a row without `parent`, `carrier`, `email` or `name`, and `createUnrelated()` for a row without `carrier` |
| `ReturnShipmentService` | A null value in a row leaves the field out of the request. A key that the API does not know is left out | Leave a field out instead of setting it to null: what a null does depends on the field, such as a `TypeError`, a `ValidationException` or an empty object in the request. A key that the API does not know goes to the API in the request body |
| `ReturnShipmentService` | A response with `data` but without `shipments` gives an empty array | It throws an `InvalidArgumentException`, because the spec requires `shipments` |
| `CapabilitiesService` and `CapabilitiesServiceInterface` | `fromShipment()` takes a `Shipment` | It takes a `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem` |
| `CapabilitiesService` and `CapabilitiesServiceInterface` | `get()` takes a `CapabilitiesRequest` and returns a `CapabilitiesResponse`. It throws a `CoreApi\ApiException` for an error response. `get()` throws an `InvalidArgumentException` before the request goes out, for a `carrier`, `packageType`, `deliveryType`, `direction`, physical `unit` or pickup location `type` that the SDK does not know, and for a country code that is not two upper case letters. It sends the User-Agent as the request body, and the request as the `User-Agent` header | `get()` and `fromShipment()` take and return the generated models: a `Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2`, built with the generated models and enums, see [Capabilities](README.md#capabilities), and a `Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2`. The fields have the camelCase names of the v2 capabilities API, such as `postalCode` and `requiresSignature`. It throws a `Jane\CoreApi\Exception\ApiException` for an error response. For a value that the spec does not know, such as a carrier, the generated client throws a `Jane\CoreApi\Runtime\Normalizer\ValidationException`, and no request goes out. The request goes out as the body, with the User-Agent of the SDK as the header |
| `CapabilitiesClientInterface` | `getCapabilities()` takes a `CapabilitiesRequest` and returns a `CapabilitiesResponse` | It takes a `Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2` and returns a `Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2`. A class that implements the interface must change its signature |
| `HttpCapabilitiesClient` | The constructor takes a `CapabilitiesMapper`. Each `getCapabilities()` call creates a client, and reads `API_KEY`, `API_KEY_NL` or `API_KEY_BE` from the environment again | The constructor takes `($apiKey, $api, $host)`, as the shipment, label and return services do: an optional API key, an optional `Jane\CoreApi\Client` with its own API key, host, headers or User-Agent, and an optional host. To give `CapabilitiesService` an API key, pass it this client: `new CapabilitiesService(new HttpCapabilitiesClient($apiKey))`. Without a client, the first call creates one and keeps it, so a later change to the environment has no effect. `getCapabilities()` returns a `Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2` |
| `CarrierContractDefinitionsService` | The constructor takes `($apiKey, $host, $api)` | It takes `($apiKey, $api, $host)`, as the other services do. A v11 call with the host as the second argument throws a `TypeError`: pass the host as the third argument |
| `CarrierContractDefinitionsService` | `getContractDefinitions()` takes a carrier name as a string, and throws an `InvalidArgumentException` for a carrier that the spec does not know | It takes a `CapabilitiesPostContractDefinitionsRequestV2Carrier` enum case, such as `CapabilitiesPostContractDefinitionsRequestV2Carrier::Postnl` for `'POSTNL'`. A string throws a PHP `TypeError`. The enum type allows only a carrier that the spec knows |
| `WebhookApiFactory` | `DEFAULT_HTTP_TIMEOUT` is a public constant | The constant is removed: to set a timeout, pass your own Symfony `HttpClientInterface` to `make()` |

### Found shipments and track & trace results

`ShipmentQueryService` returns the generated `Jane\CoreApi\Model\ShipmentDefsShipment`, and `ShipmentTrackTraceService` returns the generated `Jane\CoreApi\Model\ShipmentDefsTrackTrace`. The rules of [The Jane clients](#the-jane-clients) hold for both. These fields hold another type than the v11 getter returned:

| Field | v11 | v12 |
|-------|-----|-----|
| `carrierId`, `status`, `shipmentType`, `transactionStatus`, `paymentStatus`, `currency` and `externalProvider` of a shipment, and `carrier` of a track & trace result | `getCarrierId()`, `getStatus()`, `getShipmentType()`, `getTransactionStatus()`, `getPaymentStatus()` and `getCarrier()` return the raw value, such as `1`. `getCurrency()` and `getExternalProvider()` return a wrapper model | The enum case, such as `RefTypesCarrier::Postnl`, or the raw value for a value that the SDK does not know. Compare with the case, or read `->value` |
| `hidden`, and the flags of `options`, such as `signature` | `getHidden()` and `getOptions()->getSignature()` return `0` or `1` | The enum case `RefTypesIntBoolean::False` or `RefTypesIntBoolean::True`, or the raw value for a value that the SDK does not know. A case is an object, so `if ($shipment->hidden)` is always true: compare with the case, or read `->value` |
| `pickup`, `dropOffPoint`, `shippedItems` and `physicalProperties` | Decoded JSON with the API field names, such as `$pickup->postal_code` | `ShipmentDefsShipmentPickup`, `ShipmentDefsShipmentDropOffPoint`, `ShipmentDefsShipmentShippedItem[]` and `ShipmentDefsShipmentPhysicalProperties`: Jane models with camelCase properties, such as `$pickup->postalCode` |
| `customsDeclaration` and `collectionContact` | `ShipmentDefsShipmentCustomsDeclaration` and `ShipmentDefsShipmentCollectionContact` | `RefShipmentCustomsDeclaration` and `AccountDefsContact` |
| `origin`, `parentId`, `multiColloMainShipmentId` and `externalProviderId` | A wrapper model | `string`, `int`, `int` and `int` |
| `referenceIdentifier` | `getReferenceIdentifier()` returns a string, also for `"reference_identifier": 123` | The value as the API sends it: the int `123` |
| `createdBy` and `modifiedBy` | `int` | The value as the API sends it |
| `signature` of a track & trace result | `ShipmentDefsTrackTraceProofOfDelivery` | The decoded JSON |

### Creating shipments

`ShipmentCreateService::create()` and `ShipmentPrintService::print()` take an array of the generated `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem`. The `Shipment`, `ShipmentOptions` and `ShipmentCollection` classes of v11 are removed. Build a shipment with the generated models and their public properties, or fill one from an array with the API field names with `CoreApiModels::toModel()`:

```php
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as Shipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions as ShipmentOptions;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient as Recipient;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;

// v11
$shipment = (new Shipment())
    ->setRecipient(['cc' => 'NL', 'postal_code' => '2132JE', 'city' => 'Hoofddorp', 'street' => 'Antareslaan', 'number' => '31', 'person' => 'John Doe'])
    ->setCarrier(RefTypesCarrier::POSTNL)
    ->withPackageType('PACKAGE');
$mapping = $service->create((new ShipmentCollection())->push($shipment));

// v12, with the generated models: PHP checks each type
$recipient             = new Recipient();
$recipient->cc         = 'NL';
$recipient->postalCode = '2132JE';
$recipient->city       = 'Hoofddorp';
$recipient->street     = 'Antareslaan';
$recipient->number     = '31';
$recipient->person     = 'John Doe';

$options              = new ShipmentOptions();
$options->packageType = RefShipmentPackageType::Package;

$shipment            = new Shipment();
$shipment->carrier   = RefTypesCarrier::Postnl;
$shipment->recipient = $recipient;
$shipment->options   = $options;

// v12, from an array with the API field names: the generated client checks the values when the request goes out
$shipment = CoreApiModels::toModel([
    'carrier'   => RefTypesCarrier::Postnl->value,
    'options'   => ['package_type' => RefShipmentPackageType::Package->value],
    'recipient' => ['cc' => 'NL', 'postal_code' => '2132JE', 'city' => 'Hoofddorp', 'street' => 'Antareslaan', 'number' => '31', 'person' => 'John Doe'],
], Shipment::class);

$mapping = $service->create([$shipment]);
```

The rules of [The Jane clients](#the-jane-clients) hold for these models too: a property that you do not set is not initialized, and an enum is a PHP enum. A carrier or a package type is the id or the enum case. A delivery type is the id, such as `RefTypesDeliveryType::Standard->value`. The `number` of a recipient is a string, such as `'31'`. `setCarrier('POSTNL')` and `withPackageType('PACKAGE')` of v11 took a v2 name: use `ApiMapperService` to convert one, see [Value mapping](README.md#value-mapping).

`CoreApiModels::copy()` copies a model with its nested models, where `clone` copies only the top level. The v11 `Shipment` also had `__toString()` and `jsonSerialize()`: `(string) $shipment` and `json_encode($shipment)` gave the request JSON. Use `json_encode(CoreApiModels::toArray($shipment))`.

`MyParcelNL\Sdk\Model\Shipment\Carrier` and `PackageType` are removed. Use `ApiMapperService::forCarrier()` and `ApiMapperService::forPackageType()`, see [Value mapping](README.md#value-mapping):

| v11 `Carrier` and `PackageType` | v12 `ApiMapperService` |
|---------------------------------|------------------------|
| `toId()` | `idFromV2Name()`. It returns `null` for a name that it does not know, where `toId()` threw an `InvalidArgumentException`: check for `null` |
| `fromId()` | `v2NameFromId()`. It returns `null` for an id that it does not know, where `fromId()` threw an `InvalidArgumentException` |
| `isValid()` | `null !== ...->idFromV2Name()` |
| `all()` | `v2ToIdMap()` |

A shipment without a reference identifier gets one that starts with `sdk_`, as in v11. The services change nothing else on your models: they send copies.

### Removed classes

| Class | Replacement |
|-------|-------------|
| `MyParcelNL\Sdk\Model\Shipment\Shipment` and `ShipmentOptions` | The generated `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem` and `ShipmentPostShipmentsRequestV11DataShipmentsItemOptions`, see [Creating shipments](#creating-shipments) |
| `MyParcelNL\Sdk\Collection\ShipmentCollection` | An array of the generated shipment models |
| `MyParcelNL\Sdk\Services\Shipment\Concerns\EnsuresShipmentReferenceIds` | None. The services give a shipment without a reference identifier one that starts with `sdk_` |
| `MyParcelNL\Sdk\Services\CoreApi\Concerns\ResolvesPostShipmentsContentType` | None. The generated client picks the content type of a shipment request from the request body |
| `MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest` | The generated `Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2`: pass it to `CapabilitiesService::get()`, or pass a shipment to `CapabilitiesService::fromShipment()`. It replaces the builder with `forCountry()`, the `with*()` methods and `getUnsupportedOptions()`, which took the option names of the SDK, such as `signature`, and snake_case keys, such as `country_code`. For an option name of the SDK, `ShipmentOptionMapper::v2PropertyFromName()` gives the property of the options model, such as `requiresSignature`, or `null` for an option that the request cannot send, such as `tracked` |
| `MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse` | The generated `Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2` that `CapabilitiesService::get()` returns. `CapabilitiesResponse` merged the results into one list per kind, such as `getPackageTypes()`, which could name a combination that no single carrier contract offers. Read `$response->results`: one result per carrier contract, with `carrier`, `contract`, `packageTypes`, `deliveryTypes`, `transactionTypes`, `collo->max`, the physical limits in `physicalProperties`, and per option in `options` whether it is required, selected by default, and which options it requires or excludes. `array_keys(CoreApiModels::toArray($result->options))` gives the API names of the options, such as `requiresSignature` |
| `MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper` | None. The services take and return the generated models |

### Order push

The e-commerce service now requires a status on every order that you push with
`EcommerceApiFactory`. It refuses the whole request with HTTP 400 when one order has no status. Set
it with `Order::setStatus()` or the `status` key of the constructor array:

```php
$order->setStatus(Order::STATUS_OPEN);
```

Use `Order::STATUS_CANCELED` when the order is cancelled in the shop, and push the order again with
the same `source_id`. MyParcel sees the second push as an update of the order. See "Pushing orders"
in `README.md`.

The spec now also limits an order to 100 lines and a product to 50 children.
`Order::setLines()` and `Product::setChildren()` throw an `InvalidArgumentException` above these
limits. When you pass the lines or the children in the constructor array, nothing throws:
`listInvalidProperties()` reports the error instead.

### Generated OpenAPI models

> [!IMPORTANT]
> v12 removes the OpenAPI Generator clients and models of the Core API, the private Core API, the IAM API and the Order API: `CoreApi`, `CoreApiPrivate`, `IamApi` and `OrderApi` in `MyParcelNL\Sdk\Client\Generated`. Use the services, or the Jane clients and models in `MyParcelNL\Sdk\Client\Generated\Jane`: `CoreApi\*` becomes `Jane\CoreApi\*`, `CoreApiPrivate\*` becomes `Jane\CoreApiPrivate\*`, `IamApi\*` becomes `Jane\IamApi\*`, and `OrderApi\Model\*` becomes `Jane\OrderApi\Model\*`. No service of the SDK uses the `Jane\OrderApi` client. The Jane models use public properties instead of getters and setters, and PHP enums instead of class constants.
>
> `CoreApi\Api\NotificationApi` and the `Notification*` models are removed without a replacement: the Jane Core API client has no notification methods.
>
> A Jane client has one class per API, with other method names than the old `Api` classes: `getWhoami()` for `whoamiGet()`, and `getOrder()` for `ordersGet()`. It has no `*Async()` and no `*WithHttpInfo()` methods. `Configuration` is removed: pass the API key, the host, the User-Agent and the headers to `ShipmentApiFactory::make()` or `IamApiFactory::make()`. The SDK has no factory for `Jane\CoreApiPrivate\Client` and `Jane\OrderApi\Client`: build them with their `Client::create()`.

A Jane model has the same name as the old model when the old model came from a named schema, such as `ShipmentDefsShipmentSender`. Three groups differ. An item of an array ends in `Inner` in the old client and in `Item` in Jane, such as `CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItem`. The `oneOf`, `anyOf` and `allOf` wrappers of the old client, such as `ShipmentDefsShipmentPropertiesRegion` and `CommonResponsesDownloadLinkDataOneOf`, have no Jane class: Jane types the property itself, or leaves the type open. The table lists the names that the services of the SDK used: the old client gave some inline schemas a shorter name where Jane uses the full name of the schema, the `Fixed*` classes were overrides of the SDK for bugs of the old generator, and `RefTypesCarrierV2` came from an older spec. Other names can differ too: compare the class lists.

| v11 | v12 |
|-----|-----|
| `CoreApi\Model\ShipmentRequest` | `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem`, which `ShipmentCreateService::create()` takes, see [Creating shipments](#creating-shipments) |
| `CoreApi\Model\CapabilitiesRecipientV2`, `CapabilitiesSenderV2`, `CapabilitiesOptionsV2` and `CapabilitiesPhysicalPropertiesV2` | `Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Recipient`, `...Sender`, `...Options` and `...PhysicalProperties` |
| `CoreApi\Model\RefTypesCarrierV2` | `Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2`. The `BOL` constant has no replacement: no `Jane\CoreApi` enum has that value |
| `CoreApi\Model\SecondaryShipmentResource` | `Jane\CoreApi\Model\ShipmentDefsShipmentSecondaryShipmentsItem`, the items of `secondaryShipments` of a found shipment |
| `CoreApi\Model\SecondaryShipmentRequest` | `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem` |
| `CoreApi\Model\PhysicalPropertiesHeightV2`, `PhysicalPropertiesLengthV2`, `PhysicalPropertiesWidthV2` and `PhysicalPropertiesWeightV2` | `Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesHeight`, `...Length`, `...Width` and `...Weight` |
| `CoreApi\Model\FixedShipmentRecipient`, and `FixedShipment` of the v12 betas | `Jane\CoreApi\Model\ShipmentDefsShipment` and `ShipmentDefsShipmentRecipient`. Jane reads every region and street that the spec allows, so the overrides are not needed |
| `IamApi\Model\FixedPrincipal` | `Jane\IamApi\Model\PrincipalShop` or `PrincipalUser`, which `ApiKeyService::getPrincipal()` returns |

#### The legacy e-commerce client

The e-commerce client in `MyParcelNL\Sdk\Client\Generated\EcommerceApi` still comes from OpenAPI Generator. v12 generates it with openapi-generator 7.26.0 instead of 7.12.0:

| Class with the change | v11 | v12 |
|-----------------------|-----|-----|
| `EcommerceApi\Api\DefaultApi` | A request method throws a `JsonException` when the body cannot be encoded as JSON | It throws an `InvalidArgumentException`. The `JsonException` is its previous exception. |
| `EcommerceApi\Api\DefaultApi` | An `*Async()` method rejects the promise with an `ApiException` when the response is not valid JSON | The promise resolves with `null` |
| `EcommerceApi\Model\PhysicalProperties` | `getHeight()`, `getLength()` and `getWidth()` return `PhysicalPropertiesHeight`, `PhysicalPropertiesLength` and `PhysicalPropertiesWidth` | They return `Dimension`, and the three classes are removed. Use the `UNIT_MM`, `UNIT_CM` and `UNIT_M` constants on `Dimension`. |

`EnumFallback::setListener()` now gets only an unknown enum value of this client. The Jane clients keep an unknown value as the raw value, without a listener.

## Upgrading to v11.2

v11.2 renames generated models in the Ecommerce client. Code that uses them must change.

| Before | After |
| --- | --- |
| `EcommerceApi\Model\Product` (order line) | `EcommerceApi\Model\OrderLineProduct` |
| `EcommerceApi\Model\ProductDescriptionsInner` | `EcommerceApi\Model\OrderLineProductDescriptionsInner` |
| `EcommerceApi\Model\LengthUnit::MM` / `CM` / `M` | `PhysicalPropertiesHeight::UNIT_MM` / `UNIT_CM` / `UNIT_M` (same on `PhysicalPropertiesLength` and `PhysicalPropertiesWidth`) |

`EcommerceApi\Model\Product` still exists, but is now the body of `/webhook/products`. Code that builds order lines with it breaks. `unit` on height, length and width is now a plain string with these constants. The new `Dimension` model is generated, but `PhysicalProperties` does not use it.

v12 replaces the three `PhysicalProperties*` classes with `Dimension`. See "The legacy e-commerce client" under "Upgrading to v12".

## Upgrading to v11

v11 replaces the consignment stack with generated API clients and typed service wrappers. This guide covers every breaking change and shows before/after examples for each use case.

### Quick reference

| v10 (removed)                                  | v11 (replacement)                          |
|------------------------------------------------|--------------------------------------------|
| `MyParcelCollection` + `AbstractConsignment`   | `ShipmentCollection` + `Shipment`          |
| `ConsignmentFactory::createByCarrierId()`      | `new Shipment()` with fluent setters       |
| `MyParcelCollection::createConcepts()`         | `ShipmentCreateService::create()`          |
| `MyParcelCollection::setLatestData()`          | `ShipmentQueryService::find()` / `findMany()` / `findByReferenceId()` |
| `MyParcelCollection::deleteConcepts()`         | `ShipmentDeleteService::deleteMany()`      |
| `MyParcelCollection::setPdfOfLabels()`         | `ShipmentLabelsService::setPdfOfLabels()`  |
| `MyParcelCollection::downloadPdfOfLabels()`    | `ShipmentLabelsService::downloadPdfOfLabels()` |
| `MyParcelCollection::generateReturnConsignments()` | `ReturnShipmentService::createRelated()` |
| `MyParcelCollection::createUnrelatedReturns()` | `ReturnShipmentService::createUnrelated()` |
| `$consignment->getBarcode()` / `getBarcodeUrl()` | `ShipmentTrackTraceService::fetchTrackTraceData()` |
| Direct print via `MyParcelCollection`          | `ShipmentPrintService::print()`            |
| `AbstractWebhookWebService` + 5 subclasses     | `WebhookService`                           |
| `CheckApiKeyWebService::apiKeyIsCorrect()`     | `ApiKeyService::isValid()`                 |
| `AbstractDeliveryOptionsAdapter` + adapters     | `Shipment\ShipmentOptions` + `Order::setCarrierId()` (fulfilment orders) |

### Create shipments

#### Before (v10)

```php
use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;

$consignment = (ConsignmentFactory::createByCarrierId(PostNLConsignment::CARRIER_ID))
    ->setApiKey('your-api-key')
    ->setCountry('NL')
    ->setFullStreet('Antareslaan 31')
    ->setPerson('John Doe')
    ->setCity('Hoofddorp')
    ->setPostalCode('2132 JE')
    ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE);

$collection = (new MyParcelCollection())
    ->addConsignment($consignment);

$collection->createConcepts();
$shipmentId = $collection->first()->getMyParcelConsignmentId();
```

#### After (v11)

```php
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Shipment\ShipmentCreateService;

$shipment = (new Shipment())
    ->setRecipient([
        'cc'          => 'NL',
        'street'      => 'Antareslaan',
        'number'      => 31,
        'person'      => 'John Doe',
        'city'        => 'Hoofddorp',
        'postal_code' => '2132 JE',
    ])
    ->setCarrier(1)
    ->withPackageType('PACKAGE');

$collection = (new ShipmentCollection())->push($shipment);

$service = new ShipmentCreateService('your-api-key');
$mapping = $service->create($collection);
// $mapping = [shipmentId => referenceIdentifier, ...]
```

### Query shipments

#### Before (v10)

```php
$collection = MyParcelCollection::find(12345, 'your-api-key');
$consignment = $collection->first();
$status = $consignment->getStatus();
```

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Shipment\ShipmentQueryService;

$service  = new ShipmentQueryService('your-api-key');
$shipment = $service->find(12345);

// Or find multiple:
$shipments = $service->findMany([12345, 12346]);

// Or by reference:
$shipments = $service->findByReferenceId('order-42');
```

### Delete shipments

#### Before (v10)

```php
$collection->deleteConcepts();
```

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Shipment\ShipmentDeleteService;

$service = new ShipmentDeleteService('your-api-key');
$service->deleteMany([12345, 12346]);
```

### Labels (PDF)

#### Before (v10)

```php
$collection->setPdfOfLabels();
$pdf = $collection->getLabelPdf();
```

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Labels\ShipmentLabelsService;

$service = new ShipmentLabelsService('your-api-key');
$service->setPdfOfLabels([12345, 12346]);
$pdf = $service->getLabelPdf();

// Or get a download link:
$service->setLinkOfLabels([12345], $positions = 1);
$url = $service->getLinkOfLabels();

// Or download directly:
$service->downloadPdfOfLabels(true);
```

### Direct print (printDirect)

#### Before (v10)

```php
$collection->createConcepts();
$collection->setLinkOfLabels();
// Direct print was handled through MyParcelCollection internals
```

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Shipment\ShipmentPrintService;
use MyParcelNL\Sdk\Collection\ShipmentCollection;

$service = new ShipmentPrintService('your-api-key');
$result  = $service->print($collection, 'your-printer-group-id');
```

### Track & trace

#### Before (v10)

```php
$consignment->getBarcode();
$consignment->getBarcodeUrl();
// Or via collection:
$collection->setLatestData();
$history = $consignment->getStatus();
```

#### After (v11)

```php
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;

$service = new ShipmentTrackTraceService('your-api-key');
$traces  = $service->fetchTrackTraceData([12345]);
// Returns array of track-trace objects with status, barcode, history, etc.
```

Barcodes are available on the shipment object via `ShipmentQueryService::find()` → `getBarcode()`. The consumer portal link is on the shipment too: `getLinkConsumerPortal()`. Full track-trace history comes from `ShipmentTrackTraceService::fetchTrackTraceData()`, where each trace entry exposes `getLinkTracktrace()` for the carrier tracking URL.

### Webhooks

#### Before (v10)

```php
use MyParcelNL\Sdk\Services\Web\Webhook\ShipmentStatusChangeWebhookWebService;

$service = new ShipmentStatusChangeWebhookWebService();
$service->setApiKey('your-api-key');
$id = $service->subscribe('https://example.com/webhook');
$service->unsubscribe($id);
```

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Webhook\WebhookService;

$service = new WebhookService('your-api-key');

$id = $service->subscribe(
    WebhookService::HOOK_SHIPMENT_STATUS_CHANGE,
    'https://example.com/webhook'
);

$service->unsubscribe($id);
$all = $service->getAll();
```

Available hooks: `HOOK_SHIPMENT_STATUS_CHANGE`, `HOOK_SHIPMENT_LABEL_CREATED`, `HOOK_ORDER_STATUS_CHANGE`, `HOOK_SHOP_CARRIER_ACCESSIBILITY_UPDATED`, `HOOK_SHOP_UPDATED`.

### API key validation

#### Before (v10)

```php
use MyParcelNL\Sdk\Services\Web\CheckApiKeyWebService;

$service = new CheckApiKeyWebService();
$service->setApiKey('your-api-key');
$isValid = $service->apiKeyIsCorrect();
```

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Auth\ApiKeyService;

$service   = new ApiKeyService('your-api-key');
$isValid   = $service->isValid();
$principal = $service->getPrincipal();
```

### Capabilities

#### Before (v10)

No equivalent.

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Capabilities\CapabilitiesService;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

$service  = new CapabilitiesService();
$shipment = (new Shipment())
    ->withRecipientCountryCode('NL')
    ->withWeight(2000);

$capabilities = $service->fromShipment($shipment);
```

### Carrier contract definitions

Use `CarrierContractDefinitionsService` when you need account-level carrier contract definitions, for example to build carrier settings or discover which carriers are available for an account. Use `CapabilitiesService` when you need shipment-specific capabilities based on shipment input such as country, weight, package type or delivery options.

This is the recommended replacement when you previously used `CarrierOptionsWebService` to build carrier settings or determine which carriers are available for a shop or account. It is not a 1:1 replacement for the legacy `CarrierOptions` model; consumers should read the generated contract definition models directly.

The service returns the generated Core API contract definition models directly. The generated client is the source of truth for request validation and response shape.

#### Before (v10)

```php
use MyParcelNL\Sdk\Services\Web\CarrierOptionsWebService;

$carrierOptions = (new CarrierOptionsWebService())
    ->setApiKey('your-api-key')
    ->getCarrierOptions($shopId);
```

#### After (v11)

```php
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2;
use MyParcelNL\Sdk\Services\Capabilities\CarrierContractDefinitionsService;

$service = new CarrierContractDefinitionsService('your-api-key');

$definitions = $service->getContractDefinitions();
$inPost      = $service->getContractDefinitions(CapabilitiesPostContractDefinitionsRequestV2::CARRIER_INPOST);
```

### Return shipments

#### After (v11)

```php
use MyParcelNL\Sdk\Services\Returns\ReturnShipmentService;

$service = new ReturnShipmentService('your-api-key');

// Related return (linked to existing shipment):
$returns = $service->createRelated([
    ['parent' => 12345, 'carrier' => 1],
], true);

// Unrelated return (standalone):
$returns = $service->createUnrelated([
    ['carrier' => 1, 'recipient' => [...]],
]);
```

### Multi-collo

#### After (v11)

```php
use MyParcelNL\Sdk\Services\MultiCollo\MultiColloShipmentService;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

$service  = new MultiColloShipmentService();
$shipment = (new Shipment())->setCarrier(1)
    ->setRecipient([...]);

// Split into 3 colli:
$multiCollo = $service->splitShipment($shipment, 3);
```

### Delivery options on fulfilment orders

The old delivery options adapters (`DeliveryOptionsAdapterFactory`, `AbstractDeliveryOptionsAdapter`, etc.) parsed checkout delivery options and are fully removed. For fulfilment Order v1, shipment options now use the shared shipment options contract from `MyParcelNL\Sdk\Model\Shipment\ShipmentOptions`. Carrier remains shipment-level data and is exposed separately on the order.

#### Before (v10)

```php
use MyParcelNL\Sdk\Adapter\DeliveryOptions\DeliveryOptionsFromOrderAdapter;

$order = $collection->first();
$adapter = $order->getDeliveryOptions();
$deliveryType = $adapter->getDeliveryType();
$shipmentOptions = $adapter->getShipmentOptions();
```

#### After (v11)

```php
// ShipmentOptions is hydrated from the order API response automatically.
$order   = $collection->first();
$options = $order->getDeliveryOptions();

$deliveryType = $options->getDeliveryType();
$packageType  = $options->getPackageType();
$carrierId    = $order->getCarrierId();
$isSignature  = (bool) $options->getSignature();
```

You can also build `ShipmentOptions` manually when saving orders:

```php
use MyParcelNL\Sdk\Model\Shipment\ShipmentOptions;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;

$options = (new ShipmentOptions())
    ->setDeliveryType(RefTypesDeliveryTypeV2::MORNING)
    ->setPackageType(RefShipmentPackageTypeV2::PACKAGE)
    ->setSignature(true);

$order->setCarrierId(1);
$order->setDeliveryOptions($options);
$collection->save();
```

### What still works

The following legacy classes are still available. Classes marked `@internal` should not be used in new integrations.

- **Order v1 (fulfilment):** `OrderCollection` (@internal), `OrderNotesCollection` (@internal), `Order`, `OrderLine`, `OrderNote`, `Product`
- **Web services (legacy):** `AccountWebService` (@internal), `CarrierOptionsWebService` (@internal), `PrinterGroupWebService` (@internal). Use `CarrierContractDefinitionsService` for new account-level carrier contract definition lookups.
- **Shared models (@internal):** `BaseModel`, `Recipient`, `CustomsDeclaration`, `MyParcelCustomsItem`, `PickupLocation`, `PhysicalProperties`, `MyParcelRequest`, `RequestBody`, `PrinterlessReturnRequest`, `FullStreet`
- **Carrier value objects (@internal):** `AbstractCarrier`, `CarrierFactory`, `CarrierPostNL`, `CarrierBpost`, `CarrierDHLForYou`, `CarrierDHLEuroplus`, `CarrierDHLParcelConnect`, `CarrierDPD`, `CarrierGLS`, `CarrierBRT`, `CarrierTrunkrs`, `CarrierInPost`, `CarrierPosteItaliane`, `CarrierUPSExpressSaver`, `CarrierUPSStandard`
- **Web service infra (@internal):** `AbstractWebService`, `HasCarrier` (trait)
- **Helpers (@internal):** `SplitStreet`, `ValidateStreet`, `MyParcelCurl`, `RequestError`
- **Validation (@internal):** `AbstractValidator`, `ValidatorFactory`

These will be replaced when their respective API endpoints are added to the generated client.

### Removed classes

The following classes and namespaces have been fully removed:

- `MyParcelNL\Sdk\Model\Consignment\*` (all consignment models)
- `MyParcelNL\Sdk\Adapter\DeliveryOptions\*` (11 delivery options adapters)
- `MyParcelNL\Sdk\Helper\MyParcelCollection`
- `MyParcelNL\Sdk\Factory\ConsignmentFactory`
- `MyParcelNL\Sdk\Services\ConsignmentEncode`, `CollectionEncode`
- `MyParcelNL\Sdk\Adapter\ConsignmentAdapter`
- `MyParcelNL\Sdk\Services\Web\Webhook\*` (6 webhook web services)
- `MyParcelNL\Sdk\Services\Web\CheckApiKeyWebService`
- `MyParcelNL\Sdk\Helper\ValidatePostalCode`, `TrackTraceUrl`, `Utils`, `LabelHelper`
- `MyParcelNL\Sdk\Factory\DeliveryOptionsAdapterFactory`
- `MyParcelNL\Sdk\Exception\InvalidConsignmentException`, `NoConsignmentFoundException`
- `MyParcelNL\Sdk\Concerns\HasDebugLabels`, `HasInstance`

### Enum mapping between API versions

Use `ApiMapperService` to convert carrier, delivery type and package type values between
delivery-options names, v1 IDs and v2 names.

```php
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;

$mapper = ApiMapperService::forCarrier();

$mapper->idFromLegacyName('dhlforyou'); // 9
$mapper->v2NameFromId(9);              // 'DHL_FOR_YOU'
```

Use `forDeliveryType()` or `forPackageType()` for the other types.
Conversion methods return `null` if no mapping exists. Use `allRows()` to get the full map.
See [Value mapping](README.md#value-mapping) for usage.

`CarrierApiMapping`, `DeliveryTypeApiMapping`, `PackageTypeApiMapping` and `ApiMappingInterface`
are deprecated. Existing calls still work and throw `InvalidArgumentException` for unknown values.

Replace `RefTypesCarrierV2` with `RefCapabilitiesSharedCarrierV2`. The old class remains available in v11.

`Carrier::toId()`, `Carrier::isValid()` and `Carrier::all()` now also support `SPRING`, `VIA_TIM`
and `DHL_FREIGHT`.

`CapabilitiesRequest::withOptions()` now sends `cash_on_delivery`, `drop_off_at_postal_point`
and `extra_assurance` to the API. These options were previously ignored. `tracked` remains unsupported
in capabilities v2 requests.
Use `getUnsupportedOptions()` on the request to check which option names will be omitted.
