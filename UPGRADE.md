# Upgrade instructions

## Upgrading to v12

v12 requires PHP 8.3 or higher, up from 7.4 in v11. Please ensure your hosting environment meets this requirement.
PHP 8.2 and lower are already no longer officially supported by PHP itself.

The PHP upgrade brings along some additional breaking changes to the SDK itself. Those will be documented below.

### Services

| Class                  | v11                                | v12                                                         |
|------------------------|------------------------------------|-------------------------------------------------------------|
| `ShipmentQueryService` | `query()` ignores a key it does not know | `query()` throws an `InvalidArgumentException` for a key it does not know |
| `ShipmentQueryService` | `find()`, `findMany()`, `findByReferenceId()` and `query()` return a `CoreApi\Model\ShipmentDefsShipment` | They return a `Model\Shipment\FoundShipment` with the same getters, such as `getBarcode()`. A getter of a nested object, such as `getRecipient()`, returns a `Jane` model with public properties: `$shipment->getRecipient()->cc` |
| `ShipmentTrackTraceService` | `fetchTrackTraceData()` returns `CoreApi\Model\ShipmentDefsTrackTrace` objects | It returns `Model\TrackTrace\TrackTrace` objects with the same getters, such as `getLinkTracktrace()`. A nested object is a `Jane` model with public properties |
| `ShipmentQueryService`, `ShipmentDeleteService` and `ShipmentTrackTraceService` | The constructor takes a `CoreApi\Api\ShipmentApi` | The constructor takes a `Jane\CoreApi\Client` |
| `ShipmentQueryService`, `ShipmentDeleteService` and `ShipmentTrackTraceService` | They throw a `CoreApi\ApiException` for an error response | They throw a `Jane\CoreApi\Exception\ApiException`. Read the status code with `getResponse()->getStatusCode()` |
| `ApiKeyService`        | `getPrincipal()` returns an `IamApi\Model\Principal` with getters, such as `getShopIds()` | `getPrincipal()` returns a `Jane\IamApi\Model\PrincipalShop` or `PrincipalUser` with public properties, such as `$principal->shopIds`. `type`, `role` and `platform` are enums, or the raw string for a value the SDK does not know |
| `ApiKeyService`        | The constructor takes an `IamApi\Api\DefaultApi` | The constructor takes a `Jane\IamApi\Client`, such as the one that `IamApiFactory::make()` returns |
| `ApiKeyService`        | `isValid()` and `getPrincipal()` throw an `IamApi\ApiException` for a status other than 401 | They throw a `Jane\IamApi\Exception\ClientException`. For a principal type that the SDK does not know, they throw an `UnexpectedValueException` |
| `IamApiFactory`        | `make()` returns an `IamApi\Api\DefaultApi` | `make()` returns a `Jane\IamApi\Client`. Its `getWhoami()` returns the decoded JSON: use `ApiKeyService::getPrincipal()` for a model |

The classes in the `Jane` namespace are in `MyParcelNL\Sdk\Client\Generated\Jane`. The others are in `MyParcelNL\Sdk\Client\Generated`.

A getter of `FoundShipment` or `TrackTrace` returns `null` for a field that the API did not send. A `Jane` model does not initialize such a property, and reading it throws an `Error`. Use `??` or `isset()` on a nested object, such as `$shipment->getRecipient()->street ?? null`.

### Generated OpenAPI models

> [!IMPORTANT]
> The changes below are changes in the generated OpenAPI models in `MyParcelNL\Sdk\Client\Generated`. v12 generates them with openapi-generator 7.26.0 instead of 7.12.0. They reach you through the services, or directly when you use the generated API clients and models.

#### Through the services

| Service | Class with the change | v11 | v12 |
|---------|-----------------------|-----|-----|
| `ShipmentCreateService` and the other services that send a `Shipment` | 111 setters on the request models, such as `CoreApi\Model\RefShipmentShipmentOptions::setDeliveryDate()` | The setter throws an `InvalidArgumentException` for `null`, and a `null` in the constructor array is left out of the request | The setter accepts `null`, and the `null` is sent to the API |
| `ShipmentCreateService` and the other services that send a request body | `CoreApi\Api\ShipmentApi` | A body that cannot be encoded as JSON, for example invalid UTF-8 in a note, throws a `JsonException` | It throws an `InvalidArgumentException`. The `JsonException` is its previous exception. |
| `MultiColloShipmentService` | `CoreApi\Model\SecondaryShipmentRequest` | `splitShipment()` sets the physical properties as `ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerPhysicalProperties` | `ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties` |

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
