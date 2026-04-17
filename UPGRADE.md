# Upgrading to v11

v11 replaces the consignment stack with generated API clients and typed service wrappers. This guide covers every breaking change and shows before/after examples for each use case.

## Quick reference

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
| `AbstractDeliveryOptionsAdapter` + adapters     | `OrderShipmentOptions` (fulfilment orders) |

## Create shipments

### Before (v10)

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

### After (v11)

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

## Query shipments

### Before (v10)

```php
$collection = MyParcelCollection::find(12345, 'your-api-key');
$consignment = $collection->first();
$status = $consignment->getStatus();
```

### After (v11)

```php
use MyParcelNL\Sdk\Services\Shipment\ShipmentQueryService;

$service  = new ShipmentQueryService('your-api-key');
$shipment = $service->find(12345);

// Or find multiple:
$shipments = $service->findMany([12345, 12346]);

// Or by reference:
$shipments = $service->findByReferenceId('order-42');
```

## Delete shipments

### Before (v10)

```php
$collection->deleteConcepts();
```

### After (v11)

```php
use MyParcelNL\Sdk\Services\Shipment\ShipmentDeleteService;

$service = new ShipmentDeleteService('your-api-key');
$service->deleteMany([12345, 12346]);
```

## Labels (PDF)

### Before (v10)

```php
$collection->setPdfOfLabels();
$pdf = $collection->getLabelPdf();
```

### After (v11)

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

## Direct print (printDirect)

### Before (v10)

```php
$collection->createConcepts();
$collection->setLinkOfLabels();
// Direct print was handled through MyParcelCollection internals
```

### After (v11)

```php
use MyParcelNL\Sdk\Services\Shipment\ShipmentPrintService;
use MyParcelNL\Sdk\Collection\ShipmentCollection;

$service = new ShipmentPrintService('your-api-key');
$result  = $service->print($collection, 'your-printer-group-id');
```

## Track & trace

### Before (v10)

```php
$consignment->getBarcode();
$consignment->getBarcodeUrl();
// Or via collection:
$collection->setLatestData();
$history = $consignment->getStatus();
```

### After (v11)

```php
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;

$service = new ShipmentTrackTraceService('your-api-key');
$traces  = $service->fetchTrackTraceData([12345]);
// Returns array of track-trace objects with status, barcode, history, etc.
```

Barcodes are available on the shipment object via `ShipmentQueryService::find()` → `getBarcode()`. The consumer portal link is on the shipment too: `getLinkConsumerPortal()`. Full track-trace history comes from `ShipmentTrackTraceService::fetchTrackTraceData()`, where each trace entry exposes `getLinkTracktrace()` for the carrier tracking URL.

## Webhooks

### Before (v10)

```php
use MyParcelNL\Sdk\Services\Web\Webhook\ShipmentStatusChangeWebhookWebService;

$service = new ShipmentStatusChangeWebhookWebService();
$service->setApiKey('your-api-key');
$id = $service->subscribe('https://example.com/webhook');
$service->unsubscribe($id);
```

### After (v11)

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

## API key validation

### Before (v10)

```php
use MyParcelNL\Sdk\Services\Web\CheckApiKeyWebService;

$service = new CheckApiKeyWebService();
$service->setApiKey('your-api-key');
$isValid = $service->apiKeyIsCorrect();
```

### After (v11)

```php
use MyParcelNL\Sdk\Services\Auth\ApiKeyService;

$service   = new ApiKeyService('your-api-key');
$isValid   = $service->isValid();
$principal = $service->getPrincipal();
```

## Capabilities

### Before (v10)

No equivalent.

### After (v11)

```php
use MyParcelNL\Sdk\Services\Capabilities\CapabilitiesService;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

$service  = new CapabilitiesService();
$shipment = (new Shipment())
    ->withRecipientCountryCode('NL')
    ->withWeight(2000);

$capabilities = $service->fromShipment($shipment);
```

## Return shipments

### After (v11)

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

## Multi-collo

### After (v11)

```php
use MyParcelNL\Sdk\Services\MultiCollo\MultiColloShipmentService;
use MyParcelNL\Sdk\Model\Shipment\Shipment;

$service  = new MultiColloShipmentService();
$shipment = (new Shipment())->setCarrier(1)
    ->setRecipient([...]);

// Split into 3 colli:
$multiCollo = $service->splitShipment($shipment, 3);
```

## Delivery options on fulfilment orders

The old delivery options adapters (`DeliveryOptionsAdapterFactory`, `AbstractDeliveryOptionsAdapter`, etc.) parsed checkout delivery options and are fully removed. For fulfilment Order v1, shipment options are now read from the order API response:

### Before (v10)

```php
use MyParcelNL\Sdk\Adapter\DeliveryOptions\DeliveryOptionsFromOrderAdapter;

$order = $collection->first();
$adapter = $order->getDeliveryOptions();
$deliveryType = $adapter->getDeliveryType();
$shipmentOptions = $adapter->getShipmentOptions();
```

### After (v11)

```php
// OrderShipmentOptions is hydrated from the order API response automatically.
$order   = $collection->first();
$options = $order->getDeliveryOptions();

$deliveryType = $options->getDeliveryType();
$packageType  = $options->getPackageType();
$carrierId    = $options->getCarrierId();
$isSignature  = $options->hasSignature();
```

You can also build `OrderShipmentOptions` manually when saving orders:

```php
use MyParcelNL\Sdk\Model\Fulfilment\OrderShipmentOptions;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;

$options = (new OrderShipmentOptions())
    ->setCarrierId(1)
    ->setDeliveryType(RefTypesDeliveryTypeV2::MORNING)
    ->setPackageType(RefShipmentPackageTypeV2::PACKAGE)
    ->setSignature(true);

$order->setDeliveryOptions($options);
$collection->save();
```

## What still works

The following legacy classes are still available. Classes marked `@internal` should not be used in new integrations.

- **Order v1 (fulfilment):** `OrderCollection` (@internal), `OrderNotesCollection` (@internal), `Order`, `OrderLine`, `OrderNote`, `Product`
- **Web services (no generated-client equivalent yet):** `AccountWebService` (@internal), `CarrierOptionsWebService` (@internal), `PrinterGroupWebService` (@internal)
- **Shared models (@internal):** `BaseModel`, `Recipient`, `CustomsDeclaration`, `MyParcelCustomsItem`, `PickupLocation`, `PhysicalProperties`, `MyParcelRequest`, `RequestBody`, `PrinterlessReturnRequest`, `FullStreet`
- **Carrier value objects (@internal):** `AbstractCarrier`, `CarrierFactory`, `CarrierPostNL`, `CarrierBpost`, `CarrierDHLForYou`, `CarrierDHLEuroplus`, `CarrierDHLParcelConnect`, `CarrierDPD`, `CarrierGLS`, `CarrierTrunkrs`, `CarrierUPSExpressSaver`, `CarrierUPSStandard`
- **Web service infra (@internal):** `AbstractWebService`, `HasCarrier` (trait)
- **Helpers (@internal):** `SplitStreet`, `ValidateStreet`, `MyParcelCurl`, `RequestError`
- **Validation (@internal):** `AbstractValidator`, `ValidatorFactory`

These will be replaced when their respective API endpoints are added to the generated client.

## Removed classes

The following classes and namespaces have been fully removed:

- `MyParcelNL\Sdk\Model\Consignment\*` (13 consignment models)
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
