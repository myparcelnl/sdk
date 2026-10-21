# MyParcel PHP SDK

<a href="https://github.com/myparcelnl/sdk/releases" target="_blank"><img src="https://img.shields.io/packagist/v/myparcelnl/sdk?label=Latest%20version" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/myparcelnl/sdk" target="_blank"><img src="https://img.shields.io/packagist/dm/myparcelnl/sdk" alt="Total Downloads"></a>
<a href="https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM" target="_blank"><img src="https://img.shields.io/badge/Slack-Chat%20with%20us-white?logo=slack&labelColor=4a154b" alt="Slack support"></a>
[![Coverage Status](https://img.shields.io/codecov/c/github/myparcelnl/sdk?logo=codecov)](https://codecov.io/gh/myparcelnl/sdk)

## SDK Versions
We currently actively support three SDK versions, v10, v11 and v12.

The matrix below helps you choose: We recommend using the latest version supported by your environment.

| SDK Version | Supported PHP Versions | Active development | Bug Fixes | Security Fixes | Highlights                                                                                   |
|-------------|------------------------|--------------------|-----------|----------------|----------------------------------------------------------------------------------------------|
| v10         | 7.4 - 8.4              | ❌                 | ✅        | ✅             | Legacy consignment stack (`MyParcelCollection`).                                             |
| v11         | 7.4 - 8.4              | ❌                 | ✅        | ✅             | Generated OpenAPI clients, capabilities, MyParcel Connect. Partial order management support. |
| v12 (beta)  | 8.3 - 8.5              | ✅                 | ✅        | ✅             | New typed API clients, full order management support. Requires PHP 8.3 or higher.            |

## Requirements

- PHP >= 8.3 (8.4 and 8.5 are also supported and tested)
- Composer

```bash
composer require myparcelnl/sdk
```

## Quick start

Most v11 services take an API key in the constructor and wrap the generated API client. MyParcel
Connect is the exception, see [MyParcel Connect](#myparcel-connect).

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
| `CarrierContractDefinitionsService` | Fetch account-level carrier contract definitions |
| `WebhookService` | Subscribe, unsubscribe, list webhooks |
| `ApiKeyService` | Validate API key, get principal info |
| `ConnectService` | Connect a shop with MyParcel Connect and keep its access token usable |

See [UPGRADE.md](UPGRADE.md) for detailed before/after examples per service.

## MyParcel Connect

`ConnectService` authorises a shop in the merchant's browser instead of taking an API key. It is for
integrations you publish and other people install, rather than a single shop you run yourself.
`EcommerceApiFactory` then builds an e-commerce client that signs every call, which is how orders
are pushed.

Connect covers one call for now, `POST /webhook/orders`. Every other service in this SDK still takes
an API key.

See [PHP SDK v11] on the developer portal for how to use it. `dev/connect-poc` in this repository is
a page that runs the whole flow.

### When the connection expires

Show the merchant a notice from about one month before the connection expires, so they reconnect in
time. When the refresh token expires, the next refresh fails and the shop has to connect again.

A refresh token lasts about a year. MyParcel sends its lifetime as `refreshExpiresIn` in the answers
of `/connect/token` and `/connect/refresh`, and the SDK stores the expiry with the installation.
`ConnectService::getRefreshTokenExpiresAt()` returns it as a UTC `DateTimeImmutable`, read from
storage without a request. It returns `null` when the shop is not connected, or connected with an
SDK version that did not store the expiry yet. The first refresh after an update stores it.

```php
$expiresAt = $connect->getRefreshTokenExpiresAt();

if (null !== $expiresAt && $expiresAt < new DateTimeImmutable('+1 month')) {
    // Show the reconnect notice.
}
```

`ConnectService::getAccessTokenExpiresAt()` does the same for the access token, which lasts about an
hour. The SDK refreshes the access token itself, so you only need this date for display.

### Receiving commands

MyParcel sends commands, such as `ORDER_SHIPMENTS`, to a URL in your plugin: the `commandEndpoint`.
Pass it as the `commandEndpoint` argument of `start()`. Without it, the shop gets no commands.

```php
$url = $connect->start(
    shopName: 'My Shop',
    shopUrl: 'https://shop.example.com',
    version: '1.2.3',
    commandEndpoint: 'https://shop.example.com/myparcel/commands',
);
```

The URL must be https, at most 250 characters and without a fragment. It must also be canonical: a
lowercase host, no default port, a path, and no user name or password. `start()` throws a
`ConnectException` for a URL that breaks one of these rules.

MyParcel takes the URL when the connect flow completes. Then the SDK stores it with the
installation. `ConnectService::getCommandEndpoint()` returns it, read from storage without a request,
or `null` when MyParcel sends no commands. A reconnect replaces it, also with `null` when you leave it
out. A disconnect removes it.

Once a shop uses this endpoint, stop listening to the shipment webhooks and events of the Core API
for that shop. The commands carry the same information, and handling both ships every order twice.

#### Verifying and reading a command

Every command is a `POST` with a JSON body, signed by MyParcel with an [RFC 9421] HTTP message
signature and an [RFC 9530] `Content-Digest`. `CommandRequestVerifier::verify()` runs the checks that
[Receiving commands] lists, in that order: the signature headers, the time window, the digest of the raw body, the
key from MyParcel's JWKS, the signature itself, and the `connectionId` in the body. Run it before
anything else reads the request.

The verifier keeps MyParcel's public keys for an hour in a `JwksStorageInterface` that you write, the
same way you write `ConnectStorageInterface`. It fetches them again when they are older, or when a
command names a key it does not have, at most once a minute.

```php
use MyParcelNL\Sdk\Services\Ecommerce\CommandReader;
use MyParcelNL\Sdk\Services\Ecommerce\CommandRequestVerifier;

$rawBody  = file_get_contents('php://input');
$verifier = new CommandRequestVerifier($connect, $jwksStorage);

try {
    $verifier->verify($_SERVER['REQUEST_METHOD'], getallheaders(), $rawBody);
    $command = (new CommandReader())->read($rawBody);
} catch (\MyParcelNL\Sdk\Exception\CommandResponseInterface $exception) {
    // 401 when a check failed, 503 when the keys could not be fetched, 404 for a type this
    // plugin does not support, 400 for an invalid command. Log the message, never send it.
    http_response_code($exception->getStatusCode());
    exit;
}
```

`verify()` also throws a `ConnectException` when it cannot read the stored connection, for example
after the encryption key changed. Let that one reach your 5xx handler, so MyParcel tries again.

`$connect` is your `ConnectService`, `$jwksStorage` your `JwksStorageInterface`. Pass the body bytes
exactly as received: the digest is over those bytes, so a decoded and re-encoded body fails the check.

`CommandReader::read()` turns the body into the model for its type, such as `OrderShipmentsCommand`.
The `type`, `commandId` and `connectionId` are on every model. A status value the SDK does not know
yet stays a plain string.

#### What your plugin does after the checks

MyParcel sends each command at least once, waits 10 seconds for a reply, and retries only after a
timeout, a network error, 408, 429 or 5xx. Every other status drops the command for good.

1. Drop duplicates. Store the pair (`connectionId`, `commandId`) for at least 14 days, as an
   insert-if-absent. If the pair exists, reply 202 and do nothing else. A retry has a new signature
   but the same `commandId`.
2. Store the command and the pair in one transaction, then reply 202. If the store fails, roll back
   and reply 503.
3. Do the work after the reply, for example in a queue or a cron job. An order status change that
   sends an email takes longer than the 10 seconds MyParcel waits.
4. Never log the `Signature` header, and check that the request logging of your framework does not
   write request headers.

An `ORDER_SHIPMENTS` command holds one shipment of an order, with the shipped order lines and their
quantities. Create the shipment in your platform, or update it when you already have one for its
`shipmentId`: MyParcel can send a command for a shipment it sent before, with a new `commandId`. If
your platform cannot ship separate order lines, mark the order as shipped.

[Receiving commands] on the developer portal describes the request, the checks, and the replies in
full.

## Value mapping

Use `ApiMapperService` to convert between delivery-options names, v1 IDs and v2 names.

```php
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;

$mapper = ApiMapperService::forCarrier();

$mapper->idFromLegacyName('dhlforyou'); // 9
$mapper->v2NameFromId(9);              // 'DHL_FOR_YOU'
$mapper->legacyNameFromV2Name('DPD');  // 'dpd'
```

Use `forDeliveryType()` or `forPackageType()` for the other categories. Conversion methods return
`null` if no mapping exists. Use `allRows()` for one category or `allDomains()` for all three.

Shipment option names are handled by `Services\Mapping\ShipmentOptionMapper`. Its
`v2PropertyFromName()` method returns the generated capabilities v2 model property, or `null`.
For example, `signature` and `requiresSignature` both map to `requires_signature`.

## Unsupported capabilities options

Capabilities requests omit unknown or unsupported option names. Check the request before sending
it so your application can report these options or change the request.

```php
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;

$request = CapabilitiesRequest::forCountry('NL')
    ->withOptions(['signature' => null, 'tracked' => null]);

$request->getUnsupportedOptions(); // ['tracked']
```

This check validates option names accepted by the request model. It does not validate option
values or determine which options a carrier offers.

## Legacy services

The following are still available but will be replaced in a future release:

- **Web services (@internal):** `AccountWebService`, `CarrierOptionsWebService`, `PrinterGroupWebService`
- **Order v1 fulfilment:** `OrderCollection` (@internal), `OrderNotesCollection` (@internal), `Order`, `OrderLine`, `OrderNote`, `Product`
- **Carrier models, shared models, helpers (@internal)** — see [UPGRADE.md](UPGRADE.md) for the full list

Use `CarrierContractDefinitionsService` for new account-level carrier contract definition lookups instead of
`CarrierOptionsWebService`.

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

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines on contributing to this repository.

[Slack]: https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM
[PHP SDK v11]: https://developer.myparcel.com/guides/php-sdk-v11.html
[Receiving commands]: https://developer.myparcel.com/guides/php-sdk-v12.html#receiving-commands
[RFC 9421]: https://www.rfc-editor.org/rfc/rfc9421
[RFC 9530]: https://www.rfc-editor.org/rfc/rfc9530
[PHP SDK documentation]: https://developer.myparcel.nl/documentation/50.php-sdk.html
[MyParcel Developer Portal]: https://developer.myparcel.nl
