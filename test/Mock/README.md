# MyParcel SDK Test Mocking Guide

## Overview

This document describes how to use API mocking in the SDK tests to eliminate real HTTP calls and create deterministic, fast tests.

## Prerequisites

### 1. Environment Setup

The mock system requires `MP_SDK_TEST=true` to be set. This needs to be configured in two places:

#### For Local Development
In `phpunit.xml`:
```xml
<php>
    <env name="MP_SDK_TEST" value="true"/>
</php>
```

#### For CI/GitHub Actions
In `.github/workflows/--test.yml`:
```yaml
docker run \
  --volume $PWD:/app \
  --env MP_SDK_TEST=true \
  # ... other env vars ...
  vendor/bin/phpunit
```

This ensures the mock is active in both local development and CI environments.

### 2. Test Base Class
Your test MUST extend `TestCase` to enable automatic mocking:
```php
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class MyTest extends TestCase
{
    // Your tests here
}
```

## Quick Start - Writing a New Test

### 1. For a simple test (single API call):
```php
public function testMyFeature()
{
    // Clear the queue
    MockMyParcelCurl::clearQueue();
    
    // Add your mock response
    MockMyParcelCurl::addResponse([
        'response' => json_encode(['data' => ['result' => 'success']]),
        'code' => 200
    ]);
    
    // Run your test code
    // ...
}
```

### 2. For a consignment test (multiple API calls):
```php
public function testConsignment(array $testData): void
{
    // Clear the queue
    MockMyParcelCurl::clearQueue();
    
    // Use a dataset for standard flows
    $responses = ShipmentResponses::getPostNLFlow($testData);
    
    // Queue all responses
    foreach ($responses as $response) {
        MockMyParcelCurl::addResponse($response);
    }
    
    // Run the test
    $this->doConsignmentTest($testData);
}
```

## Architecture

### How It Works

1. **Environment Detection**: When `MP_SDK_TEST=true`, the SDK uses `MockMyParcelCurl` instead of real HTTP calls
2. **Queue System**: Tests queue responses that are returned in FIFO order
3. **Automatic Setup**: `TestCase::setUp()` automatically enables mocking for all tests

### Key Components

- **MockMyParcelCurl**: Queue-based mock that replaces `MyParcelCurl`
- **ShipmentResponses**: Dataset with reusable API responses
- **TestCase**: Base class that sets up the mock environment

## Datasets

### Where to find datasets?
- `test/Mock/Datasets/ShipmentResponses.php` - For shipment/consignment tests

### When to create a new dataset?
- When you're reusing responses for more than 2 tests
- When adding a new carrier
- When testing complex scenarios (e.g., error handling)

### Available dataset methods:

#### For standard shipment flows:
- `ShipmentResponses::getPostNLFlow($testData)` - PostNL shipments
- `ShipmentResponses::getDHLEuroplusFlow($testData)` - DHL Europlus shipments
- `ShipmentResponses::getStandardShipmentFlow($params)` - Generic shipment flow

#### For individual responses:
- `ShipmentResponses::createShipmentResponse($id, $ref)` - POST /shipments response
- `ShipmentResponses::getPdfLinkResponse($id)` - PDF link response
- `ShipmentResponses::getShipmentDetailsResponse($params)` - GET /shipments/{id} response
- `ShipmentResponses::errorResponse($code, $message)` - Error response

## Examples

### Example 1: Test with success response
```php
MockMyParcelCurl::clearQueue();
MockMyParcelCurl::addResponse([
    'response' => json_encode(['data' => ['ids' => [['id' => 12345]]]]),
    'code' => 200
]);
```

### Example 2: Test with error response
```php
MockMyParcelCurl::clearQueue();
MockMyParcelCurl::addResponse(
    ShipmentResponses::errorResponse(3505, 'Invalid postal code', 422)
);
```

### Example 3: Adding a new carrier
Add a new method to `ShipmentResponses.php`:
```php
public static function getMyNewCarrierFlow(array $testData = []): array
{
    return self::getStandardShipmentFlow([
        'carrier_id' => 99, // Your carrier ID
        'recipient' => [
            // Carrier-specific defaults
        ],
        'options' => [
            // Carrier-specific options
        ],
    ]);
}
```

## Best Practices

1. **Always clear the queue**: Start each test with `MockMyParcelCurl::clearQueue()`
2. **Queue order matters**: Responses are consumed in FIFO order
3. **Use test constants**: Use constants from `ConsignmentTestCase` (e.g., `self::SIGNATURE`, `self::EMAIL`)
4. **Match real API**: Ensure mock responses match the real API structure
5. **Test errors too**: Don't forget to test error scenarios

## Troubleshooting

### "Queue has 0 responses"
- You forgot to add responses
- Or the queue was cleared after adding responses

### "Invalid argument supplied for foreach"
- A required field is missing in your response (e.g., `secondary_shipments`)
- Check the error message for which field is missing

### Test fails with wrong value
- Check if you're passing all test data to the dataset
- Look in the dataset to see which defaults are being used
- Verify the response structure matches what the SDK expects

### Mock not being used
- Ensure `MP_SDK_TEST=true` is set
- Verify `TestCase::setUp()` is being called
- Check that your test extends the correct base class

## Migration Guide

See [EXAMPLE_UPDATE_TEST.md](EXAMPLE_UPDATE_TEST.md) for a step-by-step guide on migrating existing tests.

## Questions?

- Look at existing migrated tests as examples
- Check the PostNL/DHL tests for reference patterns
- Ask the team!
