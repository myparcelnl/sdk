MyParcel PHP SDK
===

<a href="https://github.com/myparcelnl/sdk/releases" target="_blank"><img src="https://img.shields.io/packagist/v/myparcelnl/sdk?label=Latest%20version" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/myparcelnl/sdk" target="_blank"><img src="https://img.shields.io/packagist/dm/myparcelnl/sdk" alt="Total Downloads"></a>
<a href="https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM" target="_blank"><img src="https://img.shields.io/badge/Slack-Chat%20with%20us-white?logo=slack&labelColor=4a154b" alt="Slack support"></a>
[![Coverage Status](https://img.shields.io/codecov/c/github/myparcelnl/sdk?logo=codecov)](https://codecov.io/gh/myparcelnl/sdk)


## Requirements

- PHP >=7.4
- Composer

```bash
composer require myparcelnl/sdk
```

## Quick start

All v11 services take an API key in the constructor and wrap the generated API client.

```php
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Shipment\ShipmentCreateService;

// Build a shipment
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

// Create it
$service = new ShipmentCreateService('your-api-key');
$mapping = $service->create((new ShipmentCollection())->push($shipment));
// $mapping = [shipmentId => referenceIdentifier, ...]
```

## Services

| Service | Description |
|---|---|
| `ShipmentCreateService` | Create shipments (concepts or with labels) |
| `ShipmentQueryService` | Query, find by ID, find by reference |
| `ShipmentDeleteService` | Delete concept shipments |
| `ShipmentPrintService` | Print shipments to a printer group |
| `ShipmentLabelsService` | Get label PDFs, links or direct downloads |
| `ShipmentTrackTraceService` | Fetch track & trace data |
| `ReturnShipmentService` | Create related or unrelated return shipments |
| `MultiColloShipmentService` | Split a shipment into multiple colli |
| `CapabilitiesService` | Check carrier capabilities for a shipment |
| `WebhookService` | Subscribe, unsubscribe, list webhooks |
| `ApiKeyService` | Validate API key, get principal info |

See [UPGRADE.md](UPGRADE.md) for detailed before/after examples per service.

## Legacy services

The following are still available but will be replaced in a future release:

- **Web services (@internal):** `AccountWebService`, `CarrierOptionsWebService`, `PrinterGroupWebService`
- **Order v1 fulfilment:** `OrderCollection` (@internal), `OrderNotesCollection` (@internal), `Order`, `OrderLine`, `OrderNote`, `Product`
- **Carrier models, shared models, helpers (@internal)** — see [UPGRADE.md](UPGRADE.md) for the full list

## Testing

```bash
# Run all tests
./vendor/bin/phpunit

# Run without live API tests
./vendor/bin/phpunit --exclude-group=live
```

Tests use two mocking strategies:
- **Legacy web services:** Mockery via `$this->mockCurl()` in `TestCase`
- **v11 service wrappers:** Constructor injection of a mock API instance

Existing tests serve as examples for both patterns.

## Support

For questions and support please contact us via [support@myparcel.nl](mailto:support@myparcel.nl) or chat with our
developers directly on [Slack].

## Contribute

1. Check for open issues or open a new issue to start a discussion
2. Fork the repository and branch off from `main`
3. Write tests for the new feature or bug fix
4. Ensure all tests pass: `./vendor/bin/phpunit`
5. Submit a pull request

[Slack]: https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM
[PHP SDK documentation]: https://developer.myparcel.nl/documentation/50.php-sdk.html
[MyParcel Developer Portal]: https://developer.myparcel.nl
