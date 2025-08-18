# Example: How to Mock API Calls in Tests

## Using Mockery for HTTP Requests

All tests in this SDK use **Mockery** to mock HTTP requests, ensuring that no real API calls are ever made. The `$this->mockCurl()` method, available in every test class extending `TestCase`, provides a Mockery mock object for all HTTP communications.

### BEFORE (making real API calls - DON'T DO THIS):

```php
public function testPostNLConsignments(array $testData): void
{
    // This would make REAL API calls - slow, unreliable, and expensive!
    $collection = $this->generateCollection(
        $this->createConsignmentsTestData([$testData])
    );
    
    // Real HTTP request to MyParcel API
    $collection->setLinkOfLabels();
    
    // Another real HTTP request
    $collection->fetchTrackTraceData();
    
    // Tests depend on external API - can fail for many reasons!
    $this->assertNotNull($collection->first()->getConsignmentId());
}
```

**Problems with real API calls:**
- ❌ Slow (network latency)
- ❌ Unreliable (API might be down)
- ❌ Expensive (uses API quota)
- ❌ Side effects (creates real shipments)
- ❌ Requires valid API keys
- ❌ Tests can interfere with each other

### AFTER (with Mockery - the RIGHT way):

```php
public function testPostNLConsignments(array $testData): void
{
    // 1. Prepare mock API responses
    $createShipmentResponse = [
        'response' => json_encode([
            'data' => [
                'ids' => [
                    [
                        'id' => 123456,
                        'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
                    ],
                ],
            ],
        ]),
        'headers' => [],
        'code' => 201,
    ];
    
    $trackTraceResponse = [
        'response' => json_encode([
            'data' => [
                'tracktraces' => [
                    [
                        'shipment_id' => 123456,
                        'link_tracktrace' => 'https://myparcel.me/track/123456',
                        'history' => [['event' => 'Created']]
                    ]
                ]
            ]
        ]),
        'headers' => [],
        'code' => 200,
    ];

    // 2. Set up Mockery expectations
    $mockCurl = $this->mockCurl();
    
    // Mock first API call (create shipment)
    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($createShipmentResponse);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();
    
    // Mock second API call (track trace)
    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($trackTraceResponse);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();

    // 3. Run the same test logic - but now with mocked responses!
    $collection = $this->generateCollection(
        $this->createConsignmentsTestData([$testData])
    );
    
    $collection->setLinkOfLabels(); // Uses mocked response
    $collection->fetchTrackTraceData(); // Uses mocked response
    
    // Test passes quickly and reliably!
    $this->assertNotNull($collection->first()->getConsignmentId());
}
```

**Benefits of Mockery:**
- ✅ Fast (no network calls)
- ✅ Reliable (always works)
- ✅ Free (no API quota used)
- ✅ Safe (no side effects)
- ✅ No API keys needed for testing
- ✅ Tests are isolated

## How to Mock API Calls:

Here is how you mock API calls using Mockery in your tests:

```php
public function testPostNLConsignments(array $testData): void
{
    // 1. Prepare a mock API response
    $mockResponse = [
        'response' => json_encode([
            'data' => [
                'ids' => [
                    [
                        'id' => 123456,
                        'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
                    ],
                ],
            ],
        ]),
        'headers' => [],
        'code'    => 201, // HTTP status code for created
    ];

    // 2. Get the mock CurlClient and set expectations
    $mockCurl = $this->mockCurl();

    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();

    // 3. Run the test logic that triggers the API call
    $this->doConsignmentTest($testData);
}
```

## Mocking Multiple API Calls

If your test makes multiple API calls, you can set up multiple expectations:

```php
public function testComplexFlow(): void
{
    // Prepare mock responses
    $response1 = ['response' => json_encode(['data' => ['ids' => [['id' => 1]]]]), 'code' => 201];
    $response2 = ['response' => json_encode(['data' => ['shipments' => [['id' => 1, 'status' => 2]]]]), 'code' => 200];

    // Set expectations for each call
    $mockCurl = $this->mockCurl();

    // First API call
    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($response1);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();

    // Second API call
    $mockCurl->shouldReceive('write')->once()->andReturnSelf();
    $mockCurl->shouldReceive('getResponse')->once()->andReturn($response2);
    $mockCurl->shouldReceive('close')->once()->andReturnSelf();
    
    // Your test logic here
}
```

## Key Principles for Mocking:

1.  **Count your API calls**: Ensure your `shouldReceive('...')->once()` (or `twice()`, `times(3)`, etc.) matches the number of actual calls.
2.  **Order matters**: Mockery checks expectations in the order they are defined.
3.  **Realistic responses**: Create mock responses that closely mimic the real API output.
4.  **Test for errors**: Test error scenarios by mocking non-200 HTTP status codes and error responses.

## Done! 🎉
