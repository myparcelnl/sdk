# Upgrade instructions

## Upgrading to v12

v12 requires PHP 8.3 or higher, up from 7.4 in v11. Please ensure your hosting environment meets this requirement.
PHP 8.2 and lower are already no longer officially supported by PHP itself.

The PHP upgrade brings along some additional breaking changes to the SDK itself. Those will be documented below.

### HTTP client

The generated Jane clients send their requests with a Symfony HTTP client, with an idle timeout of 10 seconds: a request stops when no data arrives for 10 seconds. In v11, the generated client stopped a request after 10 seconds in total. A slow but steady download, such as a large label PDF, now completes. An HTTP client that you pass to a factory keeps its own timeout.

A connection error or a timeout throws a Symfony `TransportExceptionInterface`, in every service that runs on a Jane client. In v11, the generated client wrapped it in an `ApiException`, and `ShipmentLabelsService`, `ShipmentPrintService` and `ReturnShipmentService` threw the PSR-18 `ClientExceptionInterface` of the HTTP client that sent the request.

### Found shipments and track & trace results

`ShipmentQueryService` returns the generated `Jane\CoreApi\Model\ShipmentDefsShipment`, and `ShipmentTrackTraceService` returns the generated `Jane\CoreApi\Model\ShipmentDefsTrackTrace`. A Jane model has public properties with camelCase names instead of getters: read `$shipment->barcode` instead of `$shipment->getBarcode()`, and `$shipment->recipient->postalCode` instead of `$shipment->getRecipient()->getPostalCode()`.

A Jane model does not initialize a property for a field that the API did not send. Reading it throws an `Error`: use `isset()` or `??`, such as `$shipment->recipient->street ?? null`. In v11, a getter returned `null`.

Some fields hold another type than the v11 getter returned:

| Field | v11 | v12 |
|-------|-----|-----|
| `carrierId`, `status`, `shipmentType`, `transactionStatus`, `paymentStatus`, `currency` and `externalProvider` of a shipment, and `carrier` of a track & trace result | `getCarrierId()`, `getStatus()`, `getShipmentType()`, `getTransactionStatus()`, `getPaymentStatus()` and `getCarrier()` return the raw value, such as `1`. `getCurrency()` and `getExternalProvider()` return a wrapper model | The enum case, such as `RefTypesCarrier::Postnl`, or the raw value for a value that the SDK does not know. Compare with the case, or read `->value` |
| `hidden` | `getHidden()` returns `0` or `1` | `0.0` or `1.0` |
| `pickup`, `dropOffPoint`, `shippedItems` and `physicalProperties` | Decoded JSON with the API field names, such as `$pickup->postal_code` | `ShipmentDefsShipmentPickup`, `ShipmentDefsShipmentDropOffPoint`, `ShipmentDefsShipmentShippedItem[]` and `ShipmentDefsShipmentPhysicalProperties`: Jane models with camelCase properties, such as `$pickup->postalCode` |
| `customsDeclaration` and `collectionContact` | `ShipmentDefsShipmentCustomsDeclaration` and `ShipmentDefsShipmentCollectionContact` | `RefShipmentCustomsDeclaration` and `AccountDefsContact` |
| `origin`, `parentId`, `multiColloMainShipmentId` and `externalProviderId` | A wrapper model | `string`, `int`, `int` and `int` |
| `referenceIdentifier` | `getReferenceIdentifier()` returns a string, also for `"reference_identifier": 123` | The value as the API sends it: the int `123` |
| `createdBy` and `modifiedBy` | `int` | The value as the API sends it |
| `signature` of a track & trace result | `ShipmentDefsTrackTraceProofOfDelivery` | The decoded JSON |

`json_encode()` of a Jane model gives the camelCase property names. Use `CoreApiModels::toArray($shipment)` for an array with the API field names, such as `postal_code`. It leaves out a field that the API did not send, and a typed field that the API sent as `null`, such as `parent_id`. The v11 models also had setters such as `setBarcode()`, array access such as `$shipment['barcode']`, `__toString()`, `valid()` and `listInvalidProperties()`. A Jane model has none of these.

### Services

| Class                  | v11                                | v12                                                         |
|------------------------|------------------------------------|-------------------------------------------------------------|
| `ShipmentQueryService` | `query()` ignores a key it does not know, leaves out a filter with the value `false`, and sends an array as `status=1&status=2` | `query()` throws an `InvalidArgumentException` for a key it does not know, or for a type that the spec does not allow, such as `'hidden' => 1`: pass `true`. It sends `false` as `0`, and an array as the `;`-separated string that the spec documents, such as `status=1;2` |
| `ApiKeyService`        | `getPrincipal()` returns an `IamApi\Model\Principal` with getters, such as `getShopIds()` | `getPrincipal()` returns a `Jane\IamApi\Model\PrincipalShop` or `PrincipalUser` with public properties, such as `$principal->shopIds`. `type` is the string `SHOP` or `USER`. `role` and `platform` are enums, or the raw string for a value that the SDK does not know. `features` and `fulfilmentPlatforms` hold `Jane\IamApi\Model\Feature` and `FulfilmentPlatform` enum cases, or the raw string for a value that the SDK does not know: compare with `Feature::OrderManagement`, or read `->value`. They are optional: read them with `$principal->features ?? []`, because a Jane model does not initialize a field that IAM leaves out |
| `ShipmentQueryService` | `find()`, `findMany()`, `findByReferenceId()` and `query()` return a `CoreApi\Model\ShipmentDefsShipment` with getters, such as `getBarcode()` | They return a `Jane\CoreApi\Model\ShipmentDefsShipment` with public properties, such as `$shipment->barcode` |
| `ShipmentTrackTraceService` | `fetchTrackTraceData()` returns `CoreApi\Model\ShipmentDefsTrackTrace` objects with getters, such as `getLinkTracktrace()` | It returns `Jane\CoreApi\Model\ShipmentDefsTrackTrace` objects with public properties, such as `$trace->linkTracktrace` |
| `ShipmentQueryService`, `ShipmentDeleteService` and `ShipmentTrackTraceService` | The constructor takes a `CoreApi\Api\ShipmentApi` | The constructor takes a `Jane\CoreApi\Client` |
| `ShipmentQueryService`, `ShipmentDeleteService` and `ShipmentTrackTraceService` | They throw a `CoreApi\ApiException` for an error response, for a connection error and for a body that is not valid JSON | They throw a `Jane\CoreApi\Exception\ApiException` for an error response. Every such exception implements `WithResponseInterface`: read the status code with `getResponse()->getStatusCode()`. A connection error or a timeout throws a Symfony `TransportExceptionInterface`. A body that is not valid JSON throws a Symfony `NotEncodableValueException`, which is not an `ApiException` |
| `ShipmentCreateService` and `ShipmentPrintService` | The constructor takes a `CoreApi\Api\ShipmentApi`. `ShipmentPrintService` also takes a PSR-18 HTTP client as the third argument | The constructor takes a `Jane\CoreApi\Client`, and the host as the third argument. To use your own HTTP client, pass a client that was built with it |
| `ShipmentCreateService` and `ShipmentPrintService` | The API rejects a shipment that does not match the spec. More than 100 shipments throw an `InvalidArgumentException` | The generated client checks the request before it sends it. It throws a `Jane\CoreApi\Runtime\Normalizer\ValidationException`, for example for a shipment without a recipient, carrier or options, for options without `package_type`, for a recipient without `street` or `postal_code`, or for more than 100 shipments |
| `ShipmentCreateService` and `ShipmentPrintService` | `ShipmentCreateService` throws a `CoreApi\ApiException` for an error response, with the status as `getCode()`. `ShipmentPrintService` throws an `InvalidArgumentException` | They throw a `Jane\CoreApi\Exception\ApiException`, such as `PostShipmentsClientErrorException`, with `getCode()` `0`: read the status with `getResponse()->getStatusCode()` |
| `MultiColloShipmentService` | `splitShipment()` takes and returns a `Shipment`. The secondary shipments are `CoreApi\Model\SecondaryShipmentRequest` objects. A shipment with an int reference identifier, such as `42`, gets a new `multi_collo_` reference identifier | It takes and returns a `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem`. The secondary shipments are `ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem` models. An int reference identifier is kept as the string `'42'` |
| `CapabilitiesService` and `CapabilitiesRequest` | `fromShipment()` takes a `Shipment` | It takes a `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem` |
| `Order` (fulfilment) | `getDeliveryOptions()` returns a `ShipmentOptions`, and `setDeliveryOptions()` takes one | They return and take a `Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions`. Fill one with `CoreApiModels::toModel(['package_type' => 1, 'delivery_type' => 2], ...Options::class)`. For an order from the API with `"insurance": 500`, `getDeliveryOptions()->insurance` is a `RefTypesPriceEuro` with `amount` `50000`, where `getInsurance()` returned `500` in v11 |
| `ShipmentLabelsService` and `ReturnShipmentService` | The constructor takes a `CoreApi\Api\ShipmentApi` and a PSR-18 HTTP client as the third argument, and the host as the fourth | The constructor takes a `Jane\CoreApi\Client`, and the host as the third argument. To use your own HTTP client, pass a client that was built with it |
| `ShipmentLabelsService` | `setLinkOfLabels()` and `setPdfOfLabels()` throw the SDK `MyParcelNL\Sdk\Exception\ApiException` for an error response, with the message `Did not receive expected label link response` or `Received payment link instead of pdf` | They throw the generated `Jane\CoreApi\Exception\ApiException` for an error response: `GetShipmentsLabelsNotFoundException` for a 404, `GetShipmentsLabelsPaymentRequiredException` for a 402 with payment instructions, and `GetShipmentsLabelsClientErrorException` or `GetShipmentsLabelsServerErrorException` for another error. A 2xx response without a link or a PDF still throws the SDK `ApiException` |
| `ReturnShipmentService` | The API rejects a return shipment that does not match the spec | The generated client checks the request before it sends it. It throws a `Jane\CoreApi\Runtime\Normalizer\ValidationException`, for example for a related return without `parent`, `carrier`, `email` or `name`, which the spec requires |
| `ReturnShipmentService` | It throws an `InvalidArgumentException` for an error response, and a `JsonException` for a body that is not valid JSON | It throws a `Jane\CoreApi\Exception\ApiException` for an error response with a JSON body, such as `PostShipmentsClientErrorException`. A body that is not valid JSON throws a Symfony `NotEncodableValueException`, and so does a row with invalid UTF-8, before the request goes out |
| `ReturnShipmentService` | A null value in a row leaves the field out of the request. A key that the API does not know is left out | A null value throws a `TypeError` before the request goes out: leave the field out. A key that the API does not know goes to the API in the request body |
| `ReturnShipmentService` | A response with `data` but without `shipments` gives an empty array | It throws an `InvalidArgumentException`, because the spec requires `shipments` |
| `CapabilitiesMapper` | `mapToCoreApi()` returns a `CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2` with getters. `mapFromCoreApi()` takes a `CoreApi\Model\CapabilitiesResponsesCapabilitiesV2`. `mapToCoreApi()` throws an `InvalidArgumentException` for a `carrier`, `packageType`, `deliveryType`, `direction`, physical `unit` or pickup location `type` that the SDK does not know, and for a country code that is not two upper case letters. It drops the `postal_code` of the sender | They use the `Jane` models with public properties. The properties have the camelCase names of the v2 capabilities API, such as `$request->recipient->countryCode`. `mapToCoreApi()` accepts every value, and the generated client rejects a value that the spec does not know when the request goes out. It throws an `InvalidArgumentException` for a physical property that is not a whole number. A numeric string, such as `'1000'`, goes out as the integer `1000`. Each key of `sender`, `physical_properties` and `pickup` is converted to the camelCase name of the Jane model, so `postal_code` goes out as `postalCode` |
| `CapabilitiesService` | It throws a `CoreApi\ApiException` for an error response. `get()` throws an `InvalidArgumentException` before the request goes out, for a `carrier`, `packageType`, `deliveryType`, `direction`, physical `unit` or pickup location `type` that the SDK does not know, and for a country code that is not two upper case letters. It sends a physical property that is not a whole number, such as `10.5`. It sends the User-Agent as the request body, and the request as the `User-Agent` header | It throws a `Jane\CoreApi\Exception\ApiException` for an error response. For a value that the spec does not know, such as a carrier, the generated client throws a `Jane\CoreApi\Runtime\Normalizer\ValidationException`, and no request goes out. A physical property that is not a whole number throws an `InvalidArgumentException`. The request goes out as the body, with the User-Agent of the SDK as the header |
| `HttpCapabilitiesClient` | Each `getCapabilities()` call creates a client, and reads `API_KEY`, `API_KEY_NL` or `API_KEY_BE` from the environment again | The first call creates the client and keeps it, so a later change to the environment has no effect. A new optional second argument of the constructor takes a `Jane\CoreApi\Client` with its own API key, host, headers or User-Agent. `getCapabilities()` throws an `UnexpectedValueException` for a v1 response |
| `CapabilitiesResponse` | `getShipmentOptions()` returns the internal property names of the generated model, such as `\0*\0container` | It returns the API names of the options that the API allows, such as `requiresSignature` |
| `CarrierContractDefinitionsService` | The constructor takes a `CoreApi\Api\ShipmentApi`. `getContractDefinitions()` returns `CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2` objects with getters | The constructor takes a `Jane\CoreApi\Client`. `getContractDefinitions()` returns `Jane` models with public properties, such as `$definition->carrier`. `carrier`, `packageTypes`, `deliveryTypes` and `transactionTypes` hold enums, such as `RefCapabilitiesSharedCarrierV2::Postnl`, or the raw string for a value that the SDK does not know. `getContractDefinitions()` also takes a `CapabilitiesPostContractDefinitionsRequestV2Carrier` enum case |
| `CarrierContractDefinitionsService` | `getContractDefinitions()` throws an `InvalidArgumentException` for a carrier that the spec does not know | It throws a `Jane\CoreApi\Runtime\Normalizer\ValidationException`, and no request goes out. It throws a `Jane\CoreApi\Exception\ApiException` for an error response |
| `WebhookService` | The constructor takes a `CoreApi\Api\WebhookApi`. `getAll()` and `getById()` return `CoreApi\Model\WebhooksResponsesWebhookV11` objects with getters, such as `getHook()` | The constructor takes a `Jane\CoreApi\Client`. `getAll()` and `getById()` return `Jane` models with public properties, such as `$subscription->hook` and `$subscription->id` |
| `WebhookService` | It throws a `CoreApi\ApiException` for an error response | It throws a `Jane\CoreApi\Exception\ApiException` |
| `WebhookApiFactory` | `make()` returns a `CoreApi\Api\WebhookApi` | `make()` returns a `Jane\CoreApi\Client` |
| `ApiKeyService`        | The constructor takes an `IamApi\Api\DefaultApi` | The constructor takes a `Jane\IamApi\Client`, such as the one that `IamApiFactory::make()` returns |
| `ApiKeyService`        | `getPrincipal()` throws an `IamApi\ApiException` for every error, also for a network error. `isValid()` returns `false` for a 401 and throws the `ApiException` for every other error | `getPrincipal()` throws a `Jane\IamApi\Exception\ServerException` for a 500 with an `application/problem+json` body, a `Jane\IamApi\Exception\ClientException` for another error status or for a 200 without a JSON body, a Symfony `TransportExceptionInterface` for a network error or a timeout, and a `Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException` for a 200 with a body that is not valid JSON. `isValid()` returns `false` for a 401 and throws the others, also for a 200 without a JSON body, where v11 returned `true`. For a principal type that the SDK does not know, `getPrincipal()` throws an `UnexpectedValueException`, and `isValid()` returns `true`, because the 200 proves the key |
| `IamApiFactory`        | `make()` returns an `IamApi\Api\DefaultApi` | `make()` returns a `Jane\IamApi\Client`. Its `getWhoami()` returns the decoded JSON: use `ApiKeyService::getPrincipal()` for a model |
| `ApiMapperService`     | `allRows()` and `allDomains()` key a row by the constant name of the openapi-generator enum, such as `DHL_FOR_YOU` | They key a row by the case name of the Jane enum, such as `DhlForYou`. The values of a row do not change |
| `ShipmentOptionMapper` | `v2PropertyFromName()` returns the property name of the openapi-generator model, such as `requires_signature` | It returns the property name of the Jane model, such as `requiresSignature`. A snake_case name, such as `requires_signature`, is still accepted as input |

A class name that starts with `Jane\CoreApi` or `Jane\IamApi` is in `MyParcelNL\Sdk\Client\Generated\Jane`, and a name that starts with `CoreApi` or `IamApi` is in `MyParcelNL\Sdk\Client\Generated`. `UnexpectedValueException` is the PHP class, and `Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException` is a class of the Jane runtime.

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
    'carrier'   => 1,
    'options'   => ['package_type' => 1],
    'recipient' => ['cc' => 'NL', 'postal_code' => '2132JE', 'city' => 'Hoofddorp', 'street' => 'Antareslaan', 'number' => '31', 'person' => 'John Doe'],
], Shipment::class);

$mapping = $service->create([$shipment]);
```

A property has the camelCase name of the API field, such as `postalCode`. A property that you do not set is not initialized: reading it throws an `Error`, so read an optional property with `??` or `isset()`. A flag such as `$options->signature` is a float, because the spec types it as a number: set `1` or `1.0`. The generated enums are PHP enums: use the case, such as `RefTypesCarrier::Postnl`, instead of the class constant `RefTypesCarrier::POSTNL`. A carrier, package type or delivery type is the id or the enum case. `setCarrier('POSTNL')` and `withPackageType('PACKAGE')` of v11 took a v2 name: use `ApiMapperService` to convert one, see [Value mapping](README.md#value-mapping).

`CoreApiModels::toArray()` encodes a model as an array with the API field names, and `CoreApiModels::copy()` copies a model with its nested models, where `clone` copies only the top level. The v11 `Shipment` also had `__toString()` and `jsonSerialize()`: `(string) $shipment` and `json_encode($shipment)` gave the request JSON. Use `json_encode(CoreApiModels::toArray($shipment))`.

The generated client checks a shipment against the spec before it sends it. A shipment that does not match, for example without a recipient, throws a `Jane\CoreApi\Runtime\Normalizer\ValidationException`, and no request goes out. `getViolationList()` lists each field and the problem. The API still checks the shipment too. A shipment without a reference identifier gets one that starts with `sdk_`, as in v11. The services change nothing else on your models: they send copies.

### Removed classes

| Class | Replacement |
|-------|-------------|
| `MyParcelNL\Sdk\Services\CoreApi\Concerns\ResolvesPostShipmentsContentType` | None. The generated client picks the content type of a shipment request from the request body |

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
> The changes below are changes in the generated OpenAPI models in `MyParcelNL\Sdk\Client\Generated`. v12 generates them with openapi-generator 7.26.0 instead of 7.12.0. They reach you through the services, or directly when you use the generated API clients and models.

#### Through the services

| Service | Class with the change | v11 | v12 |
|---------|-----------------------|-----|-----|
| The services that send a request body through `CoreApi\Api\ShipmentApi` | `CoreApi\Api\ShipmentApi` | A body that cannot be encoded as JSON, for example invalid UTF-8 in a note, throws a `JsonException` | It throws an `InvalidArgumentException`. The `JsonException` is its previous exception. |

#### Direct use of `MyParcelNL\Sdk\Client\Generated`

The rows above also apply when you use these classes directly. In addition:

| Class with the change | v11 | v12 |
|-----------------------|-----|-----|
| Every generated `Api` class, such as `CoreApi\Api\ShipmentApi` and `IamApi\Api\DefaultApi` | A request method throws a `JsonException` when the body cannot be encoded as JSON | It throws an `InvalidArgumentException`. The `JsonException` is its previous exception. |
| Every generated `Api` class | An `*Async()` method rejects the promise with an `ApiException` when the response is not valid JSON | The promise resolves with `null` |
| `EcommerceApi\Model\PhysicalProperties` | `getHeight()`, `getLength()` and `getWidth()` return `PhysicalPropertiesHeight`, `PhysicalPropertiesLength` and `PhysicalPropertiesWidth` | They return `Dimension`, and the three classes are removed. Use the `UNIT_MM`, `UNIT_CM` and `UNIT_M` constants on `Dimension`. |

## Upgrading to v11.2

v11.2 renames generated models in the Ecommerce client. Code that uses them must change.

| Before | After |
| --- | --- |
| `EcommerceApi\Model\Product` (order line) | `EcommerceApi\Model\OrderLineProduct` |
| `EcommerceApi\Model\ProductDescriptionsInner` | `EcommerceApi\Model\OrderLineProductDescriptionsInner` |
| `EcommerceApi\Model\LengthUnit::MM` / `CM` / `M` | `PhysicalPropertiesHeight::UNIT_MM` / `UNIT_CM` / `UNIT_M` (same on `PhysicalPropertiesLength` and `PhysicalPropertiesWidth`) |

`EcommerceApi\Model\Product` still exists, but is now the body of `/webhook/products`. Code that builds order lines with it breaks. `unit` on height, length and width is now a plain string with these constants. The new `Dimension` model is generated, but `PhysicalProperties` does not use it.

v12 replaces the three `PhysicalProperties*` classes with `Dimension`. See "Direct use of `MyParcelNL\Sdk\Client\Generated`" under "Upgrading to v12".

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
