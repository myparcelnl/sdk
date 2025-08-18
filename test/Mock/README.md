# MyParcel SDK Test Mocking Guide

## Overview

This document describes how to use **Mockery** for API mocking in the SDK tests to eliminate real HTTP calls and create deterministic, fast tests.

## Prerequisites

### 1. Test Base Class
Your test MUST extend `TestCase` to access the Mockery-based mocking system:

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
    // 1. Prepare mock response
    $mockResponse = [
        'response' => json_encode(['data' => ['result' => 'success']]),
        'headers' => [],
        'code' => 200
    ];
    
    // 2. Set up Mockery expectations
    $mockCurl = $this->mockCurl();
    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();
    
    // 3. Run your test code
    // ...
}
```

### 2. For a consignment test (multiple API calls):
```php
public function testConsignment(array $testData): void
{
    // 1. Prepare mock responses
    $createResponse = ['response' => json_encode(['data' => ['ids' => [['id' => 123]]]]), 'code' => 201];
    $labelResponse = ['response' => json_encode(['data' => ['pdf' => ['url' => 'test-url']]]), 'code' => 200];
    
    // 2. Set up Mockery expectations for multiple calls
    $mockCurl = $this->mockCurl();
    
    // First call: create shipment
    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($createResponse);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();
    
    // Second call: get labels
    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($labelResponse);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();
    
    // 3. Run the test
    $this->doConsignmentTest($testData);
}
```

## Architecture

### How It Works

1. **Mockery Integration**: All HTTP calls are automatically mocked using Mockery framework
2. **Expectation-Based**: Tests define expectations for API calls using `shouldReceive()`
3. **Automatic Setup**: `TestCase::setUp()` automatically enables mocking for all tests

### Key Components

- **Mockery**: Modern PHP mocking framework that replaces all HTTP calls
- **TestCase::mockCurl()**: Method that provides Mockery mock instances
- **ShipmentResponses**: Dataset with reusable API responses (still available for reference)
- **TestCase**: Base class that integrates Mockery mocking

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
$mockResponse = [
    'response' => json_encode(['data' => ['ids' => [['id' => 12345]]]]),
    'headers' => [],
    'code' => 200
];

$mockCurl = $this->mockCurl();
$mockCurl->shouldReceive('write')->once()->andReturnSelf();
$mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
$mockCurl->shouldReceive('close')->once()->andReturnSelf();
```

### Example 2: Test with error response
```php
$errorResponse = [
    'response' => json_encode([
        'message' => 'Invalid postal code',
        'errors' => [['code' => 3505, 'message' => 'Invalid postal code']]
    ]),
    'headers' => [],
    'code' => 422
];

$mockCurl = $this->mockCurl();
$mockCurl->shouldReceive('write')->once()->andReturnSelf();
$mockCurl->shouldReceive('getResponse')->once()->andReturn($errorResponse);
$mockCurl->shouldReceive('close')->once()->andReturnSelf();
```

### Example 3: Multiple API calls with different responses
```php
$response1 = ['response' => json_encode(['data' => ['ids' => [['id' => 1]]]]), 'code' => 201];
$response2 = ['response' => json_encode(['data' => ['pdf' => ['url' => 'test-url']]]), 'code' => 200];

$mockCurl = $this->mockCurl();

// First call
$mockCurl->shouldReceive('write')->once()->andReturnSelf();
$mockCurl->shouldReceive('getResponse')->once()->andReturn($response1);
$mockCurl->shouldReceive('close')->once()->andReturnSelf();

// Second call
$mockCurl->shouldReceive('write')->once()->andReturnSelf();
$mockCurl->shouldReceive('getResponse')->once()->andReturn($response2);
$mockCurl->shouldReceive('close')->once()->andReturnSelf();
```

## Best Practices

1. **Count your API calls**: Ensure your `shouldReceive('...')->once()` (or `times(N)`) matches actual calls
2. **Order matters**: Mockery validates expectations in the order they are defined
3. **Use realistic responses**: Create mock responses that closely mimic real API output
4. **Test error scenarios**: Mock error responses with appropriate HTTP status codes
5. **Use `$this->mockCurl()`**: Always get mock instances from the TestCase method

## Troubleshooting

### "Method write(<Any Arguments>) should be called exactly N times but called M times"
- Your expectations don't match the actual number of API calls
- Count how many HTTP requests your test code makes
- Update your `shouldReceive('write')->times(N)` to match

### "No matching handler found"
- You're missing a `shouldReceive()` expectation for a method call
- Add expectations for all methods: `write()`, `getResponse()`, `close()`

### "Invalid argument supplied for foreach"
- A required field is missing in your mock response
- Check the error message for which field is missing
- Verify your mock response structure matches the real API

### Test fails with unexpected values
- Check if your mock response data matches what the test expects
- Verify you're returning the correct HTTP status codes
- Ensure response structure matches real API responses

### Mock expectations not being verified
- Make sure you're calling `$mockCurl = $this->mockCurl()` 
- Verify your test extends `TestCase` which integrates Mockery
- Check that Mockery is properly installed as a dependency

## Migration Guide

See [EXAMPLE_UPDATE_TEST.md](EXAMPLE_UPDATE_TEST.md) for a step-by-step guide on migrating existing tests.

## Questions?

- Look at existing migrated tests as examples
- Check the PostNL/DHL tests for reference patterns
- Ask the team!
